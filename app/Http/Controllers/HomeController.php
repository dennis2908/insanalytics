<?php

namespace App\Http\Controllers;

use Analytics;	

use Illuminate\Support\Str;

use Carbon\Carbon;

use Spatie\Analytics\Period;

use DateTime;

use DB;

use DataTables;

use Illuminate\Http\Request;

class HomeController extends Controller
{
	function APIDateDuration(){
	
		dd(Analytics::fetchVisitorsAndPageViews(Period::create(new DateTime('2020-01-01'),new DateTime('2020-12-01'))));

		//dd(Analytics::getTopKeyWordsForPeriod("2010-02-10", "2010-02-10", 30));
	}
	
	function indexPage(){
		$mytime = Carbon::now();
		
		//dd(date('Y-m-d',strtotime($mytime->toDateTimeString().' - 2 days')));
		set_time_limit(0);
		//$analytic = Analytics::fetchVisitorsAndPageViews(Period::create(new DateTime(date('Y',strtotime($mytime->toDateTimeString()))."-01-01"),new DateTime(date('Y-m-d',strtotime($mytime->toDateTimeString())))));
		//$analytic = Analytics::fetchVisitorsAndPageViews(Period::days(1));
		
		//dd($analytic[2126]);
		//$key = array_keys(array_column($analytic->toArray(),'pageTitle'),'Poskota');
		//dd($key);
		/*
		$arr = [
		0=>'ga:pagePath=@2020/10/29/50-pelancong-di-puncak-bogor-reaktif-covid-19',
		1=>'ga:pagePath=@2020/11/4/beredar-di-medsos-informasi-puluhan-buaya-terlepas-dari-penangkaran-bpbd-bogor-belum-tahu',
		2=>'ga:pagePath=@2020/11/4/petugas-gabungan-kabupaten-bogor-gelar-patroli-sosialisasi-prokes-covid-19',
		3=>'ga:pagePath=@2020/11/1/viralpemotor-gelantungan-di-tiang-listrik-hindari-krl-melintas',
		];
		*/
		//$data = implode(",",$arr).';ga:pageTitle!~404 Page';
		$data = 'ga:pageTitle!~404 Page;ga:pageTitle!~503 Service Unavailable;ga:pagePathLevel1!~/iklan-baris/';
		//dd($data );
		$analyticsData = Analytics::performQuery(Period::create(new DateTime(date('Y-m-d',strtotime($mytime->toDateTimeString().' - 2 days'))),new DateTime(date('Y-m-d',strtotime($mytime->toDateTimeString().' - 2 days')))),
		'ga:',
		[
			'metrics' => 'ga:users,ga:newUsers,ga:sessions,ga:sessionsPerUser,ga:pageviews,ga:pageviewsPerSession,ga:avgSessionDuration,ga:bounceRate',
			'dimensions' => 'ga:pageTitle,ga:pagePath,ga:dateHour',
			'filters' => $data
		]);
		
		$datad = $analyticsData['rows'];
		//dd($datad);
		//dd(array_column($analyticsData['rows'],'1'));
		$dataDB = array_column(\DB::table(DB::raw('poskota_dev_whee_remote.posts_server'))->selectRaw('CONCAT("/",DATE_FORMAT(publish_date, "%Y/%c/%d"),"/",post_slug) as slug')->whereIn(\DB::raw('CONCAT("/",DATE_FORMAT(publish_date, "%Y/%c/%d"),"/",post_slug)'),array_column($analyticsData['rows'],'1'))->get()->toArray(),'slug');
		//dd($dataDB);	
		//$datacol = array_column($dataDB->toArray(),'slug');
		//dd($datad[0]['rows']);
		/*
		$datadIns = array_map(function($datad) {
			dd($datad[0]);
			if(in_array($datad['1'],$datad[0]['rows'])){
				return array(
					'pagePath' => $datad['1'],
					'dateHourMinute' => $datad['2'],
					'users' => $datad['3'],
					'newUsers' => $datad['4'],
					'sessions' => $datad['5'],
					'sessionsPerUser' => $datad['6'],
					'pageviews' => $datad['7'],
					'pageviewsPerSession' => $datad['8'],
					'avgSessionDuration' => $datad['9'],
					'bounceRate' => $datad['10'],
				);
			}
		}, $datad);	
		*/
		
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
		
	   //dd($newdata);
		
		//DB::table('t_analytics')->insert($datadIns);
		$analytics= array_chunk($newdata, 500, true);
		
		foreach ($analytics as $key => $analytic) {
		  DB::table('t_analytics')->insert($analytic);
		}
		//dd($analyticsData['rows']);
		//$key = array_keys(array_column($analyticsData['rows'],'0'),'Ledakan di Kalideres Diduga Dari Tabung Gas - Poskota News');
		//dd($key);
		//return view('analytic')->with('data', $analytic);
		//return view('analytic')->with('data');
		//dd(Analytics::getTopKeyWordsForPeriod("2010-02-10", "2010-02-10", 30));
	}
	
	function generalOld(Request $request){
		
		set_time_limit(0);
		if ($request->ajax()) {

            $data = DB::table(DB::raw('poskota_dev_whee_remote.users_server'))->selectRaw('poskota_dev_whee_remote.users_server.name,email,sum(users) as sumusers,sum(pageviews) as sumpageviews,sum(bounceRate)/count(pageviews) as bounceRateAvg')
			->leftJoin(DB::raw('poskota_dev_whee_remote.posts_server'),'poskota_dev_whee_remote.posts_server.created_by','=',DB::RAW('poskota_dev_whee_remote.users_server.id'))
			->join('t_analytics','t_analytics.pagePath','=',DB::raw('CONCAT("/",DATE_FORMAT(poskota_dev_whee_remote.posts_server.publish_date, "%Y/%c/%d"),"/",poskota_dev_whee_remote.posts_server.post_slug)'))
			->leftJoin('role_users','role_users.user_id','=','poskota_dev_whee_remote.users_server.id')
			->where('role_id',2)->groupBy(['poskota_dev_whee_remote.users_server.id','poskota_dev_whee_remote.users_server.name','email']);
			
			$sql = Str::replaceArray('?', $data->getBindings(), $data->toSql());
			$dataAll = DB::select('select * from('.$sql.')b');

        //dd($sql);

            return Datatables::of($dataAll)->addIndexColumn()->make(true);

        }

        return view('user');


	}
	
	function general(Request $request){
		
		set_time_limit(0);
		if ($request->ajax()) {

            $data = DB::connection('mysql_prd')->table('users')->selectRaw('id,name,email')
			->join('posts','posts.created_by','=','users.id')
			->join('role_users','role_users.user_id','=','users.id')
			->where('role_id',2)->groupBy(['users.id','name','email']);
			
			$sql = Str::replaceArray('?', $data->getBindings(), $data->toSql());
			$dataAll = DB::select('select * from('.$sql.')b');

        //dd($sql);

            return Datatables::of($dataAll)->addIndexColumn()
			->addColumn('dataAnalytics',function ($data){
			
			$dataPosts = \DB::connection('mysql_prd')->table('users')->selectRaw('CONCAT("/",DATE_FORMAT(publish_date, "%Y/%c/%d"),"/",post_slug) as slug')
			->leftJoin('posts','posts.created_by','=','users.id')
			->leftJoin('role_users','role_users.user_id','=','users.id')
			->where('users.id',$data->id)->get()->toArray();
			$get['visitors'] = 0;
			$get['viewers'] = 0;
			$get['bounceRate'] = 0;
			if($dataPosts){
				//dd($dataPosts);
				$dataPosts_slug = array_column($dataPosts,'slug');
				$arr = \DB::table('t_analytics')->select(['users','pageviews','bounceRate'])->whereIn('pagePath',$dataPosts_slug)->get()->toArray();	
				
				if($arr){
					$get['visitors']= array_sum(array_column($arr,'users'));
					$get['viewers']= array_sum(array_column($arr,'pageviews'));
					$get['bounceRate']= array_sum(array_column($arr,'bounceRate'))/count(array_column($arr,'pageviews'));
				}
			}

            return $get;
			})
			->make(true);

        }

        return view('user');


	}
}