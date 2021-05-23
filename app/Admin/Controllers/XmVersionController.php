<?php

namespace App\Admin\Controllers;

use App\Admin\Repositories\XmVersion;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use Dcat\Admin\Http\Controllers\AdminController;

class XmVersionController extends AdminController
{
    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Grid::make(new XmVersion(), function (Grid $grid) {
            $grid->column('id')->sortable();
            $grid->column('url');
            $grid->column('v_code');
            $grid->column('created_at');
            $grid->column('updated_at')->sortable();
        
            $grid->filter(function (Grid\Filter $filter) {
                $filter->equal('id');
        
            });
        });
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     *
     * @return Show
     */
    protected function detail($id)
    {
        return Show::make($id, new XmVersion(), function (Show $show) {
            $show->field('id');
            $show->field('url');
            $show->field('v_code');
            $show->field('created_at');
            $show->field('updated_at');
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Form::make(new XmVersion(), function (Form $form) {
            $form->display('id');
            $form->text('url');
            $form->text('v_code');
        
            $form->display('created_at');
            $form->display('updated_at');
        });
    }
}
