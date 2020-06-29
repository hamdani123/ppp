<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::get('/dashboard', 'DashboardController@get_data_dashboard');
// Route::post('/register', 'AuthController@register');
Route::post('/login', 'AuthController@login');
// Route::post('/recover', 'AuthController@recover');

Route::group(['middleware' => ['jwt.auth']], function() {
    Route::group(['prefix' => 'user'], function(){
        Route::post('/', 'AuthController@add_user');
        Route::get('/', 'AuthController@get_all_user');
        Route::post('/delete', 'AuthController@delete_user');
    });

    Route::group(['prefix' => 'proyek'], function(){
        Route::post('/', 'ProyekController@tambah_proyek');
        Route::get('/', 'ProyekController@get_semua_proyek');
        Route::post('/delete', 'ProyekController@delete_proyek_by_id');
        Route::post('/update', 'ProyekController@update_proyek');
        
        Route::group(['prefix' => 'penjaminan'], function(){
            Route::post('/', 'ProyekController@tambah_penjaminan_proyek');
            Route::post('/delete', 'ProyekController@delete_penjaminan_proyek');
            Route::post('/update', 'ProyekController@update_penjaminan_proyek');
            Route::get('/', 'ProyekController@get_penjaminan_proyek');

            Route::group(['prefix' => 'analisis_resiko'], function(){
                Route::post('/', 'ProyekController@tambah_analisis_resiko');
                Route::post('/delete', 'ProyekController@delete_analisis_resiko');
                Route::post('/update', 'ProyekController@update_analisis_resiko');
                Route::get('/', 'ProyekController@get_analisis_resiko');
            });
        });

        Route::get('/jenis_infrastruktur', 'ProyekController@get_jenis_infrastruktur');
        Route::post('/posisi_timeline/update', 'ProyekController@update_posisi_timeline');

        Route::post('/timeline', 'ProyekController@tambah_timeline_proyek');
        Route::get('/timeline/all', 'ProyekController@get_timeline_all');
        Route::get('/timeline', 'ProyekController@get_timeline_proyek');
        Route::post('/timeline/update', 'ProyekController@update_timeline_proyek');
        Route::post('/timeline/delete', 'ProyekController@delete_timeline_proyek');
    
        Route::post('/tahapan', 'ProyekController@tambah_tahapan_proyek');
        Route::get('/tahapan', 'ProyekController@get_tahapan_proyek');
        Route::get('/tahapan/with_deleted', 'ProyekController@get_tahapan_proyek_with_deleted');
        Route::post('/tahapan/delete', 'ProyekController@delete_tahapan_proyek');
        Route::post('/tahapan/update', 'ProyekController@update_tahapan_proyek');
        Route::get('/tahapan/dropdown', 'ProyekController@get_tahapan_proyek_dropdown');
        Route::get('/tahapan/history', 'ProyekController@get_history_tahapan');

        Route::post('/issue', 'ProyekController@tambah_issue');
        Route::post('/issue/delete', 'ProyekController@delete_issue');
        Route::post('/issue/update', 'ProyekController@update_issue');
        Route::get('/issue', 'ProyekController@get_semua_issue');
        Route::get('/issue/single', 'ProyekController@get_issue_by_proyek_or_tahapan');

        Route::post('/dokumen_tahapan', 'ProyekController@tambah_dokumen_tahapan_proyek');
        Route::post('/dokumen_tahapan/delete', 'ProyekController@delete_dokumen_tahapan_proyek');
        Route::post('/dokumen_tahapan/update', 'ProyekController@update_dokumen_tahapan_proyek');
        Route::get('/dokumen_tahapan', 'ProyekController@get_dokumen_tahapan_proyek');

        Route::group(['prefix' => 'pemegang_saham'], function(){
            Route::post('/', 'ProyekController@tambah_pemegang_saham_proyek');
            Route::post('/delete', 'ProyekController@delete_pemegang_saham_proyek');
            Route::post('/update', 'ProyekController@update_pemegang_saham_proyek');
            Route::get('/', 'ProyekController@get_pemegang_saham_proyek');
        });

        Route::group(['prefix' => 'badan_usaha'], function(){
            Route::post('/', 'ProyekController@tambah_badan_usaha');
            Route::post('/delete', 'ProyekController@delete_badan_usaha');
            Route::post('/update', 'ProyekController@update_badan_usaha');
            Route::get('/semua', 'ProyekController@get_semua_badan_usaha');
            Route::get('/', 'ProyekController@get_badan_usaha');
        });

        Route::get('/{id}', 'ProyekController@get_proyek_by_id');
    });

    Route::group(['prefix' => 'pjpk'], function(){
        Route::post('/proyek', 'PJPKController@tambah_proyek');
        Route::post('/proyek/update', 'PJPKController@update_proyek');
        Route::get('/proyek/{id?}', 'PJPKController@get_proyek_by_pjpk_id');
        // Route::get('/proyek', 'PJPKController@get_proyek_by_pjpk_id');

        Route::post('/instansi', 'PJPKController@tambah_instansi_terkait');
        Route::post('/instansi/update', 'PJPKController@update_instansi_terkait');
        Route::get('/instansi/{id?}', 'PJPKController@get_instansi_terkait_by_pjpk_id');
        // Route::get('/instansi', 'PJPKController@get_instansi_terkait_by_pjpk_id');

        Route::post('/update', 'PJPKController@update_pjpk');
        Route::post('/', 'PJPKController@tambah_pjpk');
        Route::get('/', 'PJPKController@get_semua_pjpk');
        Route::get('/{id}', 'PJPKController@get_pjpk_by_id');
    });
    
    Route::group(['prefix' => 'advisor'], function(){
        Route::post('/proyek', 'AdvisorController@tambah_advisor_proyek');
        Route::post('/proyek/update', 'AdvisorController@update_advisor_proyek');
        Route::post('/proyek/delete', 'AdvisorController@delete_advisor_proyek');

        Route::post('/', 'AdvisorController@tambah_advisor');
        Route::post('/delete', 'AdvisorController@delete_advisor');
        Route::post('/update', 'AdvisorController@update_advisor');
        Route::get('/', 'AdvisorController@get_semua_advisor');

        Route::get('/{id}', 'AdvisorController@get_detail_advisor');


    });

    Route::group(['prefix' => 'development_agencies'], function(){
        Route::post('/proyek', 'DevelopmentAgenciesController@tambah_development_agencies_proyek');
        Route::post('/proyek/update', 'DevelopmentAgenciesController@update_development_agencies_proyek');
        Route::post('/proyek/delete', 'DevelopmentAgenciesController@delete_development_agencies_proyek');

        Route::post('/', 'DevelopmentAgenciesController@tambah_development_agencies');
        Route::post('/delete', 'DevelopmentAgenciesController@delete_development_agencies');
        Route::post('/update', 'DevelopmentAgenciesController@update_development_agencies');
        Route::get('/', 'DevelopmentAgenciesController@get_semua_development_agencies');

        Route::get('/{id}', 'DevelopmentAgenciesController@get_detail_development_agencies');


    });

    Route::group(['prefix' => 'investor'], function(){

        Route::post('/', 'InvestorController@tambah_investor');
        Route::post('/delete', 'InvestorController@delete_investor');
        Route::post('/update', 'InvestorController@update_investor');
        Route::get('/', 'InvestorController@get_semua_investor');

        Route::get('/{id}', 'InvestorController@get_detail_investor');


    });

    Route::get('/dropdown', 'DropdownController@dropdown_list');
    Route::get('/checklist', 'DropdownController@check_list');

    Route::get('/logout', 'AuthController@logout');
});


