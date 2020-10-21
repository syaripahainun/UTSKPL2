<?php

namespace App\Http\Controllers;

use App\Company; //File Model
use App\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\AllowedFilter;

class DashboardCSMController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Short description here.
     *
     * @return void
     */
    public function companies()
    {
        $companies = DB::table('pawoon2.companies')
        ->select(DB::raw("COUNT('pawoon2.companies') as total"))
        ->join('pawoon1.user_companies', 'pawoon1.user_companies.company_id', '=', 'pawoon2.companies.id')
        ->get();
        return response()->json([
            'success' => true,
            'message' => 'Berhasil Count',
            'data' => [
                'companies' => $companies,
            ],
        ], 200)
        ->header('Access-Control-Allow-Origin', '*');
    }

    /**
     * Short description here.
     *
     * @return void
     */
    public function outlets()
    {
        $outlets = DB::table('pawoon2.outlets')
        ->select(DB::raw("COUNT('pawoon2.outlets') as total"))
        ->join('pawoon2.companies', 'pawoon2.outlets.company_id', '=', 'pawoon2.companies.id')
        ->join('pawoon1.user_companies', 'pawoon1.user_companies.company_id', '=', 'pawoon2.companies.id')
        ->get();
        return response()->json([
            'success' => true,
            'message' => 'Jumlah Outlets',
            'data' => [
                'outlets' => $outlets,
            ],
        ], 200)
        ->header('Access-Control-Allow-Origin', '*');
    }
    
    /**
     * Short description here.
     *
     * @return void
     */
    public function devices()
    {
        $devices = DB::table('pawoon2.devices')
        ->select(DB::raw("COUNT('pawoon2.devices') as total"))
        ->join('pawoon2.companies', 'pawoon2.devices.company_id', '=', 'pawoon2.companies.id')
        ->join('pawoon1.user_companies', 'pawoon1.user_companies.company_id', '=', 'pawoon2.companies.id')
        ->get();
        return response()->json([
            'success' => true,
            'message' => 'Jumlah Outlets',
            'data' => [
                'devices' => $devices,
            ],
        ], 200)
        ->header('Access-Control-Allow-Origin', '*');
    }

    /**
     * Short description here.
     *
     * @return void
     */
    public function transactions()
    {
        $transactions = DB::table('pawoon2.transactions')
        ->select(DB::raw("COUNT('pawoon2.transactions') as total"))
        ->join('pawoon2.outlets', 'pawoon2.transactions.outlet_id', '=', 'pawoon2.outlets.id')
        ->join('pawoon2.companies', 'pawoon2.outlets.company_id', '=', 'pawoon2.companies.id')
        ->join('pawoon1.user_companies', 'pawoon1.user_companies.company_id', '=', 'pawoon2.companies.id')
        ->get();
        return response()->json([
            'success' => true,
            'message' => 'Jumlah Transactions',
            'data' => [
                'transactions' => $transactions,
            ],
        ], 200)
        ->header('Access-Control-Allow-Origin', '*');
    }

    /**
     * Short description here.
     *
     * @return void
     */
    public function listtransactions()
    {
        //$user = UserCompany::where('user_id', $id)->first();
        $transactions = DB::table('pawoon2.transactions')
        ->select('pawoon2.transactions.id as id', 'pawoon2.transactions.uuid', 'pawoon2.transactions.receipt_code', 'pawoon2.transactions.customer_name', 'pawoon2.transactions.final_amount')
        //->offset($start)
        ->limit(10000)
        ->get();

        return response()->json([
            'success' => true,
            'message' => 'Berhasil Melihat Table Transactions',
            'data' => [
                'transactions' => $transactions,
            ],
        ], 200)
        ->header('Access-Control-Allow-Origin', '*');
    }

    /**
     * Short description here.
     *
     * @return void
     */
    public function sumfinalamount()
    {
        $transactions = DB::table('pawoon2.transactions')
       
            ->select(
                DB::raw("SUM(if(MONTH(device_timestamp) = '01', final_amount,0)) as total1"),
                DB::raw("SUM(if(MONTH(device_timestamp) = '02', final_amount,0)) as total2"),
                DB::raw("SUM(if(MONTH(device_timestamp) = '03', final_amount,0)) as total3"),
                DB::raw("SUM(if(MONTH(device_timestamp) = '04', final_amount,0)) as total4"),
                DB::raw("SUM(if(MONTH(device_timestamp) = '05', final_amount,0)) as total5"),
                DB::raw("SUM(if(MONTH(device_timestamp) = '06', final_amount,0)) as total6"),
                DB::raw("SUM(if(MONTH(device_timestamp) = '07', final_amount,0)) as total7"),
                DB::raw("SUM(if(MONTH(device_timestamp) = '08', final_amount,0)) as total8"),
                DB::raw("SUM(if(MONTH(device_timestamp) = '09', final_amount,0)) as total9"),
                DB::raw("SUM(if(MONTH(device_timestamp) = '10', final_amount,0)) as total10"),
                DB::raw("SUM(if(MONTH(device_timestamp) = '11', final_amount,0)) as total11"),
                DB::raw("SUM(if(MONTH(device_timestamp) = '12', final_amount,0)) as total12")
            )
            
            ->whereYear('device_timestamp', 2018)
            //->groupBy(DB::raw("YEAR(device_timestamp)"))
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Berhasil Menghitung Transactions',
            'data' => [
                'transactions' => $transactions,
            ],
        ], 200)
        ->header('Access-Control-Allow-Origin', '*');
    }

    /**
     * Short description here.
     *
     * @return void
     */
    public function countcompany()
    {
        $companies = DB::table('pawoon2.devices')
            ->select(
                DB::raw("COUNT(if(MONTH(pawoon2.devices.created_at) = '01', pawoon2.devices.id,0)) as total1"),
                DB::raw("COUNT(if(MONTH(pawoon2.devices.created_at) = '02', pawoon2.devices.id,0)) as total2"),
                DB::raw("COUNT(if(MONTH(pawoon2.devices.created_at) = '03', pawoon2.devices.id,0)) as total3"),
                DB::raw("COUNT(if(MONTH(pawoon2.devices.created_at) = '04', pawoon2.devices.id,0)) as total4"),
                DB::raw("COUNT(if(MONTH(pawoon2.devices.created_at) = '05', pawoon2.devices.id,0)) as total5"),
                DB::raw("COUNT(if(MONTH(pawoon2.devices.created_at) = '06', pawoon2.devices.id,0)) as total6"),
                DB::raw("COUNT(if(MONTH(pawoon2.devices.created_at) = '07', pawoon2.devices.id,0)) as total7"),
                DB::raw("COUNT(if(MONTH(pawoon2.devices.created_at) = '08', pawoon2.devices.id,0)) as total8"),
                DB::raw("COUNT(if(MONTH(pawoon2.devices.created_at) = '09', pawoon2.devices.id,0)) as total9"),
                DB::raw("COUNT(if(MONTH(pawoon2.devices.created_at) = '10', pawoon2.devices.id,0)) as total10"),
                DB::raw("COUNT(if(MONTH(pawoon2.devices.created_at) = '11', pawoon2.devices.id,0)) as total11"),
                DB::raw("COUNT(if(MONTH(pawoon2.devices.created_at) = '12', pawoon2.devices.id,0)) as total12")
            )
            //print_r($companies->toSql());
            
            ->join('pawoon2.companies', 'pawoon2.devices.company_id', '=', 'pawoon2.companies.id')
            ->join('pawoon1.user_companies', 'pawoon1.user_companies.company_id', '=', 'pawoon2.companies.id')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Berhasil Menghitung companies',
            'data' => [
                'companies' => $companies,
            ],
        ], 200)
        ->header('Access-Control-Allow-Origin', '*');
    }
}
