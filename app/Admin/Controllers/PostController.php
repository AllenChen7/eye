<?php

namespace App\Admin\Controllers;

use App\Admin\Repositories\Post;
use App\Models\AdminUser;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use Dcat\Admin\Http\Controllers\AdminController;

class PostController extends AdminController
{
    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Grid::make(new Post(), function (Grid $grid) {
            $grid->column('id')->sortable();
            $grid->column('admin_user_id')->display(function ($id) {
                return AdminUser::find($id)->name;
            });
            $grid->column('title');
            $grid->column('desc');
            $grid->column('image');
            $grid->column('status')->display(function ($status) {
                return $status ? '已发布' : '待发布';
            });
            $grid->column('view_nums');
            $grid->column('notify_nums');
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
        return Show::make($id, new Post(), function (Show $show) {
            $show->field('id');
            $show->field('admin_user_id');
            $show->field('title');
            $show->field('desc');
            $show->field('image');
            $show->field('status');
            $show->field('view_nums');
            $show->field('notify_nums');
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
        return Form::make(new Post(), function (Form $form) {
            $form->display('id');
            $form->text('admin_user_id');
            $form->text('title');
            $form->text('desc');
            $form->text('image');
            $form->text('status');
            $form->text('view_nums');
            $form->text('notify_nums');

            $form->display('created_at');
            $form->display('updated_at');
        });
    }
}
