<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\menu_module;
use App\Models\sub_module_menu;
use App\Models\role_has_permission;

class NewsubmenuCreationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // menu_module::create(['role_id'=>1, 'name'=>'NewMenuName_With_Permission_Check', 'icoClass'=>'FontAwsome_calssname_for_this_menu','status'=>'0/1','menuLink'=>'#','aliasName'=>'MenuNameToDisplay','sorting_order'=>'MenuListingOrder' ]);

        // $menu=menu_module::create(['user_role_id'=>1, 'name'=>'HumanResource', 'icoClass'=>'fas fa-user-tie','status'=>1,'menuLink'=>'#','aliasName'=>'Human Resource','sorting_order'=>5]);


        // $submenuid = sub_module_menu::create(['user_role_id'=>1, 'parentModuleID'=>'2', 'sorting_order'=>'5','name'=>'ULB Report','menuLink'=>'/tender/tender/UlbReport','aliasName'=>'ULB Report','status'=>'1', 'createdby'=>'1' ]);

        // $menu=menu_module::create(['user_role_id'=>1, 'name'=>'Expenses', 'icoClass'=>'fas fa-money','status'=>1,'menuLink'=>'#','aliasName'=>'Expenses','sorting_order'=>6]);


        // $submenuid = sub_module_menu::create(['user_role_id'=>1, 'parentModuleID'=>$menu->id, 'sorting_order'=>'1','name'=>'OtherExpenses','menuLink'=>'/tender/otherExpense','aliasName'=>'Other Expenses','status'=>'1', 'createdby'=>'1' ]);


        $submenuid = sub_module_menu::create(['user_role_id'=>1, 'parentModuleID'=>'5', 'sorting_order'=>'3','name'=>'HolidayMaster','menuLink'=>'/tender/hr/holidays','aliasName'=>'Holiday Master','status'=>'1', 'createdby'=>'1' ]);

        // $menu2=menu_module::create(['user_role_id'=>1, 'name'=>'Expenses', 'icoClass'=>'fas fa-user-tie','status'=>1,'menuLink'=>'#','aliasName'=>'Expenses','sorting_order'=>6]);
//  $submenuid1 = sub_module_menu::create(['user_role_id'=>1, 'parentModuleID'=>6, 'sorting_order'=>'2','name'=>'ReimbursementForm','menuLink'=>'/tender/expenses/Reimbursement','aliasName'=>'Reimbursement Form','status'=>'1', 'createdby'=>'1' ]);
        // $submenuid = sub_module_menu::create(['user_role_id'=>1, 'parentModuleID'=>'4', 'sorting_order'=>'2','name'=>'call_to_bdm','menuLink'=>'/tender/calllog/calltobdm/','aliasName'=>'Call to BDM','status'=>'1', 'createdby'=>'1' ]);

        // $submenuid = sub_module_menu::create(['user_role_id'=>1, 'parentModuleID'=>1, 'sorting_order'=>'16','name'=>'expense_type','menuLink'=>'/tender/master/expensetype/','aliasName'=>'Expense Type','status'=>'1', 'createdby'=>'1' ]);
        // $submenuid = sub_module_menu::create(['user_role_id'=>1, 'parentModuleID'=>5, 'sorting_order'=>'2','name'=>'attendance_report','menuLink'=>'/tender/hr/attendancereport','aliasName'=>'Attendance Report','status'=>'1', 'createdby'=>'1' ]);
        // $submenuid = sub_module_menu::create(['user_role_id'=>1, 'parentModuleID'=>$menu->id, 'sorting_order'=>'18','name'=>'attendance_type','menuLink'=>'/tender/hr/attendancetype','aliasName'=>'Attendance Type Master','status'=>'1', 'createdby'=>'1' ]);

/**********Navin */
$submenuid = sub_module_menu::create(['user_role_id'=>1, 'parentModuleID'=>1, 'sorting_order'=>'19','name'=>'BusinessForecastStatus','menuLink'=>'/tender/master/BusinessForecastStatus','aliasName'=>'Business Forecast Status','status'=>'1', 'createdby'=>'1' ]);
// $submenuid = sub_module_menu::create(['user_role_id'=>1, 'parentModuleID'=>1, 'sorting_order'=>'16','name'=>'expense_type','menuLink'=>'/tender/master/expensetype/','aliasName'=>'Expense Type','status'=>'1', 'createdby'=>'1' ]);
        

/********** */


      //  $submenuid = sub_module_menu::create(['user_role_id'=>1, 'parentModuleID'=>$menu->id, 'sorting_order'=>'18','name'=>'attendance_type','menuLink'=>'/tender/hr/attendancetype','aliasName'=>'Attendance Type Master','status'=>'1', 'createdby'=>'1' ]);

        // $submenuid = sub_module_menu::create(['user_role_id'=>1, 'parentModuleID'=>$menu->id, 'sorting_order'=>'18','name'=>'attendance_type','menuLink'=>'/tender/hr/attendancetype','aliasName'=>'Attendance Type Master','status'=>'1', 'createdby'=>'1' ]);

        

        // $role_has_permission = role_has_permission::create(['permission_id' => 0,'role_id'=>1, 'menu_modules_id'=> 1,'submenu_modules_id'=> $submenuid->id, 'can_view'=> 1,'can_add'=> 1,'can_edit'=> 1,'can_delete'=> 1]);

    }

    //seeding commend   
    // php artisan db:seed --class=NewsubmenuCreationSeeder
}

