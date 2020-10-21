<?php
/**
 * Short description here.
 *
 * PHP version 5
 *
 * @category Foo
 * @package Foo_Helpers
 * @author Marty McFly <mmcfly@example.com>
 * @copyright 2013-2014 Foo Inc.
 * @license MIT License
 * @link http://example.com
 */
namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use App\Devices;
use App\Transaction;
use App\UserCompany;
use Spatie\QueryBuilder\AllowedFilter;

/**
 * The Foo class.
 */
class LaporanCSEController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */


    //Devices
    public function index($userId)
    {
        $devices = DB::table('pawoon2.devices')
        ->select(DB::raw('distinct pawoon2.devices.name as device_name, pawoon2.devices.id, pawoon2.companies.name as company_name, pawoon2.users.email as email_owner, pawoon2.transactions.device_timestamp as last_transaction, pawoon2.roles_2.name as tier'))
        ->join('pawoon2.companies', 'pawoon2.devices.company_id', '=', 'pawoon2.companies.id')
        ->join('pawoon2.outlets', 'pawoon2.outlets.company_id', '=', 'pawoon2.companies.id')
        ->join('pawoon2.transactions', 'pawoon2.transactions.outlet_id', '=', 'pawoon2.outlets.id')
        ->join('pawoon1.user_companies', 'pawoon1.user_companies.company_id', '=', 'pawoon2.companies.id')
        ->join('pawoon2.user_has_companies', 'pawoon2.companies.id', '=', 'pawoon2.user_has_companies.company_id')
        ->join('pawoon2.users', 'pawoon2.users.id', '=', 'pawoon2.user_has_companies.user_id')
        ->join('pawoon2.model_has_roles', 'pawoon2.model_has_roles.model_id', '=', 'pawoon2.users.id')
        ->join('pawoon2.roles', 'pawoon2.model_has_roles.role_id', '=', 'pawoon2.roles.id')
        ->join('pawoon2.model_has_roles as model_has_roles_2', 'pawoon2.model_has_roles_2.model_id', '=', 'pawoon2.companies.id')
        ->join('pawoon2.roles as roles_2', function ($join) {
            $join->on('pawoon2.model_has_roles_2.role_id', '=', 'pawoon2.roles_2.id')
                ->where('pawoon2.model_has_roles_2.model_type', 'App\Company');
        })
        ->where('pawoon2.roles.id', '1')
        ->where('pawoon1.user_companies.user_id', $userId)
        ->whereNull('pawoon2.devices.deleted_at')
        ->groupBy('pawoon2.devices.id')

        // dd($devices->toSql());
         ->get();

        return response()->json([
            'success' => true,
            'message' => 'Berhasil Show devices',
            'data' => [
                'users' => $devices,
            ],
        ], 200)
        ->header('Access-Control-Allow-Origin', '*');
    }

    //Company
    public function show($userId)
    {
        $users = DB::table('pawoon2.users')
        ->select(DB::raw('distinct pawoon2.roles_2.name as tier, pawoon2.companies.name as company_name, pawoon2.users.email as email_owner, pawoon2.transactions.final_amount as transaction_amount, pawoon2.transactions.device_timestamp as Tanggal'))
        ->where('pawoon2.roles.id', '1')
        ->where('pawoon1.user_companies.user_id', $userId)
        ->join('pawoon2.user_has_companies', 'pawoon2.users.id', '=', 'pawoon2.user_has_companies.user_id')
        ->join('pawoon2.companies', 'pawoon2.companies.id', '=', 'pawoon2.user_has_companies.company_id')
        ->join('pawoon1.user_companies', 'pawoon1.user_companies.company_id', '=', 'pawoon2.companies.id')
        ->join('pawoon2.model_has_roles', 'pawoon2.model_has_roles.model_id', '=', 'pawoon2.user_has_companies.id')
        ->join('pawoon2.roles', 'pawoon2.roles.id', '=', 'pawoon2.model_has_roles.role_id')
        ->join('pawoon2.outlets', 'pawoon2.outlets.company_id', '=', 'pawoon2.companies.id')
        ->join('pawoon2.transactions', 'pawoon2.transactions.outlet_id', '=', 'pawoon2.outlets.id')
        ->join('pawoon2.model_has_roles as model_has_roles_2', 'pawoon2.model_has_roles_2.model_id', '=', 'pawoon2.companies.id')
        ->join('pawoon2.roles as roles_2', function ($join) {
            $join->on('pawoon2.model_has_roles_2.role_id', '=', 'pawoon2.roles_2.id')
                ->where('pawoon2.model_has_roles_2.model_type', 'App\Company');
        })
        ->groupBy('pawoon2.companies.id')
        ->get();

        return response()->json([
            'success' => true,
            'message' => 'Berhasil Show User',
            'data' => [
                'user' => $users,
            ],
        ], 200)
        ->header('Access-Control-Allow-Origin', '*');
        //}
    }

    //Outlets
    public function showoutlets($userId)
    {
        $outlets = DB::table('pawoon2.outlets')
        ->select(DB::raw('distinct pawoon2.outlets.id, pawoon2.outlets.uuid, pawoon2.outlets.name as outlet_name, pawoon2.companies.name as company_name, pawoon2.users.email as email_owner, pawoon2.roles_2.name as tier'))
            //DB::raw("SUM(if(DATE(device_timestamp) = '2020-01-13', final_amount,0))as grand_total"))
        ->where('pawoon2.roles.id', '1')
        ->where('pawoon1.user_companies.user_id', $userId)
        ->join('pawoon2.companies', 'pawoon2.outlets.company_id', '=', 'pawoon2.companies.id')
        ->join('pawoon2.transactions', 'pawoon2.outlets.id', '=', 'pawoon2.transactions.outlet_id')
        ->join('pawoon2.user_has_companies', 'pawoon2.companies.id', '=', 'pawoon2.user_has_companies.company_id')
        ->join('pawoon2.users', 'pawoon2.users.id', '=', 'pawoon2.user_has_companies.user_id')
        ->join('pawoon1.user_companies', 'pawoon1.user_companies.company_id', '=', 'pawoon2.companies.id')
        ->join('pawoon2.model_has_roles', 'pawoon2.model_has_roles.model_id', '=', 'pawoon2.user_has_companies.id')
        ->join('pawoon2.roles', 'pawoon2.roles.id', '=', 'pawoon2.model_has_roles.role_id')
        ->join('pawoon2.model_has_roles as model_has_roles_2', 'pawoon2.model_has_roles_2.model_id', '=', 'pawoon2.companies.id')
        ->join('pawoon2.roles as roles_2', function ($join) {
            $join->on('pawoon2.model_has_roles_2.role_id', '=', 'pawoon2.roles_2.id')
                ->where('pawoon2.model_has_roles_2.model_type', 'App\Company');
        })
        ->get();


        return response()->json([
            'success' => true,
            'message' => 'Berhasil Show outlets',
            'data' => [
                'user' => $outlets,
            ],
        ], 200)
        ->header('Access-Control-Allow-Origin', '*');
        //}
    }

    //OutletsReport
    public function reportoutlet($uid)
    {
        $transactions = DB::table('pawoon2.transactions')
        ->select(DB::raw('distinct pawoon2.outlets.name as outlet_name, pawoon2.companies.name as company_name, pawoon2.devices.name as device_name,FORMAT(pawoon2.transactions.final_amount,2, "id_ID") as amount, pawoon2.transactions.device_timestamp as Tanggal')) /*DB::raw("SUM(final_amount) as grand_total"))
        ->whereDate('device_timestamp', '2020-01-13')*/
        ->where('pawoon2.outlets.id', $uid)
        ->join('pawoon2.outlets', 'pawoon2.transactions.outlet_id', '=', 'pawoon2.outlets.id')
        ->join('pawoon2.companies', 'pawoon2.outlets.company_id', '=', 'pawoon2.companies.id')
        ->join('pawoon2.devices', 'pawoon2.transactions.device_id', '=', 'pawoon2.devices.id')
        ->get();


        return response()->json([
            'success' => true,
            'message' => 'Berhasil Show outlets',
            'data' => [
                'user' => $transactions,
            ],
        ], 200)
        ->header('Access-Control-Allow-Origin', '*');
        //}
    }

    //SumAmount
    public function sumamount($uid)
    {
        $transactions = DB::table('pawoon2.transactions')
        ->select(DB::raw("FORMAT(SUM(final_amount),2 , 'id_ID')as total"))
        ->where('pawoon2.outlets.id', $uid)
        ->join('pawoon2.outlets', 'pawoon2.transactions.outlet_id', '=', 'pawoon2.outlets.id')
        ->get();


        return response()->json([
            'success' => true,
            'message' => 'Berhasil Sum Amount',
            'data' => [
                'user' => $transactions,
            ],
        ], 200)
        ->header('Access-Control-Allow-Origin', '*');
        //}
    }
}
