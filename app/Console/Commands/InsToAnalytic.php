<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Analytics;	

use Illuminate\Support\Str;

use Carbon\Carbon;

use Spatie\Analytics\Period;

use DateTime;

use DB;

class InsToAnalytic extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'InsToAnalytic';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Insert data to google api analytic';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $mytime = Carbon::now();
		
		$twoDaysAgo = date('Y-m-d',strtotime($mytime->toDateTimeString().' - 2 days'));
		set_time_limit(0);
		
		$data = 'ga:pageTitle!~404 Page;ga:pageTitle!~503 Service Unavailable;ga:pagePathLevel1!~/iklan-baris/';
		$analyticsData = Analytics::performQuery(Period::create(new DateTime($twoDaysAgo),new DateTime($twoDaysAgo)),
		'ga:',
		[
			'metrics' => 'ga:users,ga:newUsers,ga:sessions,ga:sessionsPerUser,ga:pageviews,ga:pageviewsPerSession,ga:avgSessionDuration,ga:bounceRate',
			'dimensions' => 'ga:pageTitle,ga:pagePath,ga:dateHour',
			'filters' => $data
		]);
		$datad = $analyticsData['rows'];
		$dataDB = array_column(\DB::connection('mysql_prd')->table(DB::connection('mysql_prd')->raw('posts'))->selectRaw('CONCAT("/",DATE_FORMAT(publish_date, "%Y/%c/%d"),"/",post_slug) as slug')->whereIn(\DB::connection('mysql_prd')->raw('CONCAT("/",DATE_FORMAT(publish_date, "%Y/%c/%d"),"/",post_slug)'),array_column($analyticsData['rows'],'1'))->get()->toArray(),'slug');		
		$newdata = [];
		$k=0;
		foreach($datad as &$val){
		if(in_array($val['1'],$dataDB)){	
			$newdata[$k]['pagePath'] = $val['1'];
			$newdata[$k]['dateHourMinute'] = $val['2'];
			$newdata[$k]['users'] = $val['3'];
			$newdata[$k]['newUsers'] = $val['4'];
			$newdata[$k]['sessions'] = $val['5'];
			$newdata[$k]['sessionsPerUser']= $val['6'];
			$newdata[$k]['pageviews'] = $val['7'];
			$newdata[$k]['pageviewsPerSession'] = $val['8'];
			$newdata[$k]['avgSessionDuration'] = $val['9'];
			$newdata[$k]['bounceRate'] = $val['10'];
			$k++;
		}	
			
	   }

		$analytics= array_chunk($newdata, 500, true);
		
		foreach ($analytics as $key => $analytic) {
		  DB::connection('mysql')->table('t_analytics')->insert($analytic);
		}
    }
}
