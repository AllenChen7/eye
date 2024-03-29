<?php

/**
 * A helper file for Dcat Admin, to provide autocomplete information to your IDE
 *
 * This file should not be included in your code, only analyzed by your IDE!
 *
 * @author jqh <841324345@qq.com>
 */
namespace Dcat\Admin {
    use Illuminate\Support\Collection;

    /**
     * @property Grid\Column|Collection created_at
     * @property Grid\Column|Collection detail
     * @property Grid\Column|Collection id
     * @property Grid\Column|Collection name
     * @property Grid\Column|Collection type
     * @property Grid\Column|Collection updated_at
     * @property Grid\Column|Collection version
     * @property Grid\Column|Collection is_enabled
     * @property Grid\Column|Collection extension
     * @property Grid\Column|Collection icon
     * @property Grid\Column|Collection order
     * @property Grid\Column|Collection parent_id
     * @property Grid\Column|Collection uri
     * @property Grid\Column|Collection menu_id
     * @property Grid\Column|Collection permission_id
     * @property Grid\Column|Collection http_method
     * @property Grid\Column|Collection http_path
     * @property Grid\Column|Collection slug
     * @property Grid\Column|Collection role_id
     * @property Grid\Column|Collection user_id
     * @property Grid\Column|Collection value
     * @property Grid\Column|Collection avatar
     * @property Grid\Column|Collection password
     * @property Grid\Column|Collection remember_token
     * @property Grid\Column|Collection username
     * @property Grid\Column|Collection area_id
     * @property Grid\Column|Collection city_id
     * @property Grid\Column|Collection create_user_id
     * @property Grid\Column|Collection province_id
     * @property Grid\Column|Collection status
     * @property Grid\Column|Collection connection
     * @property Grid\Column|Collection exception
     * @property Grid\Column|Collection failed_at
     * @property Grid\Column|Collection payload
     * @property Grid\Column|Collection queue
     * @property Grid\Column|Collection class_data_id
     * @property Grid\Column|Collection model_id
     * @property Grid\Column|Collection model_type
     * @property Grid\Column|Collection guard_name
     * @property Grid\Column|Collection grade_id
     * @property Grid\Column|Collection is_del
     * @property Grid\Column|Collection plan_date
     * @property Grid\Column|Collection plan_user
     * @property Grid\Column|Collection remark
     * @property Grid\Column|Collection year_class_id
     * @property Grid\Column|Collection admin_user_id
     * @property Grid\Column|Collection desc
     * @property Grid\Column|Collection image
     * @property Grid\Column|Collection notify_nums
     * @property Grid\Column|Collection view_nums
     * @property Grid\Column|Collection last_user_id
     * @property Grid\Column|Collection birthday
     * @property Grid\Column|Collection glasses_type
     * @property Grid\Column|Collection id_card
     * @property Grid\Column|Collection is_glasses
     * @property Grid\Column|Collection is_myopia
     * @property Grid\Column|Collection join_school_date
     * @property Grid\Column|Collection l_axi
     * @property Grid\Column|Collection l_axis
     * @property Grid\Column|Collection l_cyl
     * @property Grid\Column|Collection l_degree
     * @property Grid\Column|Collection l_roc1
     * @property Grid\Column|Collection l_roc2
     * @property Grid\Column|Collection l_sph
     * @property Grid\Column|Collection plan_id
     * @property Grid\Column|Collection r_axi
     * @property Grid\Column|Collection r_axis
     * @property Grid\Column|Collection r_cyl
     * @property Grid\Column|Collection r_degree
     * @property Grid\Column|Collection r_roc1
     * @property Grid\Column|Collection r_roc2
     * @property Grid\Column|Collection r_sph
     * @property Grid\Column|Collection sex
     * @property Grid\Column|Collection student_code
     * @property Grid\Column|Collection student_id
     * @property Grid\Column|Collection plan_status
     * @property Grid\Column|Collection email
     * @property Grid\Column|Collection email_verified_at
     * @property Grid\Column|Collection phone
     * @property Grid\Column|Collection power_type
     * @property Grid\Column|Collection power_user_id
     * @property Grid\Column|Collection wx_user_id
     * @property Grid\Column|Collection city
     * @property Grid\Column|Collection country
     * @property Grid\Column|Collection gender
     * @property Grid\Column|Collection language
     * @property Grid\Column|Collection nickname
     * @property Grid\Column|Collection nums
     * @property Grid\Column|Collection openid
     * @property Grid\Column|Collection province
     * @property Grid\Column|Collection session_key
     * @property Grid\Column|Collection union_id
     * @property Grid\Column|Collection url
     * @property Grid\Column|Collection v_code
     *
     * @method Grid\Column|Collection created_at(string $label = null)
     * @method Grid\Column|Collection detail(string $label = null)
     * @method Grid\Column|Collection id(string $label = null)
     * @method Grid\Column|Collection name(string $label = null)
     * @method Grid\Column|Collection type(string $label = null)
     * @method Grid\Column|Collection updated_at(string $label = null)
     * @method Grid\Column|Collection version(string $label = null)
     * @method Grid\Column|Collection is_enabled(string $label = null)
     * @method Grid\Column|Collection extension(string $label = null)
     * @method Grid\Column|Collection icon(string $label = null)
     * @method Grid\Column|Collection order(string $label = null)
     * @method Grid\Column|Collection parent_id(string $label = null)
     * @method Grid\Column|Collection uri(string $label = null)
     * @method Grid\Column|Collection menu_id(string $label = null)
     * @method Grid\Column|Collection permission_id(string $label = null)
     * @method Grid\Column|Collection http_method(string $label = null)
     * @method Grid\Column|Collection http_path(string $label = null)
     * @method Grid\Column|Collection slug(string $label = null)
     * @method Grid\Column|Collection role_id(string $label = null)
     * @method Grid\Column|Collection user_id(string $label = null)
     * @method Grid\Column|Collection value(string $label = null)
     * @method Grid\Column|Collection avatar(string $label = null)
     * @method Grid\Column|Collection password(string $label = null)
     * @method Grid\Column|Collection remember_token(string $label = null)
     * @method Grid\Column|Collection username(string $label = null)
     * @method Grid\Column|Collection area_id(string $label = null)
     * @method Grid\Column|Collection city_id(string $label = null)
     * @method Grid\Column|Collection create_user_id(string $label = null)
     * @method Grid\Column|Collection province_id(string $label = null)
     * @method Grid\Column|Collection status(string $label = null)
     * @method Grid\Column|Collection connection(string $label = null)
     * @method Grid\Column|Collection exception(string $label = null)
     * @method Grid\Column|Collection failed_at(string $label = null)
     * @method Grid\Column|Collection payload(string $label = null)
     * @method Grid\Column|Collection queue(string $label = null)
     * @method Grid\Column|Collection class_data_id(string $label = null)
     * @method Grid\Column|Collection model_id(string $label = null)
     * @method Grid\Column|Collection model_type(string $label = null)
     * @method Grid\Column|Collection guard_name(string $label = null)
     * @method Grid\Column|Collection grade_id(string $label = null)
     * @method Grid\Column|Collection is_del(string $label = null)
     * @method Grid\Column|Collection plan_date(string $label = null)
     * @method Grid\Column|Collection plan_user(string $label = null)
     * @method Grid\Column|Collection remark(string $label = null)
     * @method Grid\Column|Collection year_class_id(string $label = null)
     * @method Grid\Column|Collection admin_user_id(string $label = null)
     * @method Grid\Column|Collection desc(string $label = null)
     * @method Grid\Column|Collection image(string $label = null)
     * @method Grid\Column|Collection notify_nums(string $label = null)
     * @method Grid\Column|Collection view_nums(string $label = null)
     * @method Grid\Column|Collection last_user_id(string $label = null)
     * @method Grid\Column|Collection birthday(string $label = null)
     * @method Grid\Column|Collection glasses_type(string $label = null)
     * @method Grid\Column|Collection id_card(string $label = null)
     * @method Grid\Column|Collection is_glasses(string $label = null)
     * @method Grid\Column|Collection is_myopia(string $label = null)
     * @method Grid\Column|Collection join_school_date(string $label = null)
     * @method Grid\Column|Collection l_axi(string $label = null)
     * @method Grid\Column|Collection l_axis(string $label = null)
     * @method Grid\Column|Collection l_cyl(string $label = null)
     * @method Grid\Column|Collection l_degree(string $label = null)
     * @method Grid\Column|Collection l_roc1(string $label = null)
     * @method Grid\Column|Collection l_roc2(string $label = null)
     * @method Grid\Column|Collection l_sph(string $label = null)
     * @method Grid\Column|Collection plan_id(string $label = null)
     * @method Grid\Column|Collection r_axi(string $label = null)
     * @method Grid\Column|Collection r_axis(string $label = null)
     * @method Grid\Column|Collection r_cyl(string $label = null)
     * @method Grid\Column|Collection r_degree(string $label = null)
     * @method Grid\Column|Collection r_roc1(string $label = null)
     * @method Grid\Column|Collection r_roc2(string $label = null)
     * @method Grid\Column|Collection r_sph(string $label = null)
     * @method Grid\Column|Collection sex(string $label = null)
     * @method Grid\Column|Collection student_code(string $label = null)
     * @method Grid\Column|Collection student_id(string $label = null)
     * @method Grid\Column|Collection plan_status(string $label = null)
     * @method Grid\Column|Collection email(string $label = null)
     * @method Grid\Column|Collection email_verified_at(string $label = null)
     * @method Grid\Column|Collection phone(string $label = null)
     * @method Grid\Column|Collection power_type(string $label = null)
     * @method Grid\Column|Collection power_user_id(string $label = null)
     * @method Grid\Column|Collection wx_user_id(string $label = null)
     * @method Grid\Column|Collection city(string $label = null)
     * @method Grid\Column|Collection country(string $label = null)
     * @method Grid\Column|Collection gender(string $label = null)
     * @method Grid\Column|Collection language(string $label = null)
     * @method Grid\Column|Collection nickname(string $label = null)
     * @method Grid\Column|Collection nums(string $label = null)
     * @method Grid\Column|Collection openid(string $label = null)
     * @method Grid\Column|Collection province(string $label = null)
     * @method Grid\Column|Collection session_key(string $label = null)
     * @method Grid\Column|Collection union_id(string $label = null)
     * @method Grid\Column|Collection url(string $label = null)
     * @method Grid\Column|Collection v_code(string $label = null)
     */
    class Grid {}

    class MiniGrid extends Grid {}

    /**
     * @property Show\Field|Collection created_at
     * @property Show\Field|Collection detail
     * @property Show\Field|Collection id
     * @property Show\Field|Collection name
     * @property Show\Field|Collection type
     * @property Show\Field|Collection updated_at
     * @property Show\Field|Collection version
     * @property Show\Field|Collection is_enabled
     * @property Show\Field|Collection extension
     * @property Show\Field|Collection icon
     * @property Show\Field|Collection order
     * @property Show\Field|Collection parent_id
     * @property Show\Field|Collection uri
     * @property Show\Field|Collection menu_id
     * @property Show\Field|Collection permission_id
     * @property Show\Field|Collection http_method
     * @property Show\Field|Collection http_path
     * @property Show\Field|Collection slug
     * @property Show\Field|Collection role_id
     * @property Show\Field|Collection user_id
     * @property Show\Field|Collection value
     * @property Show\Field|Collection avatar
     * @property Show\Field|Collection password
     * @property Show\Field|Collection remember_token
     * @property Show\Field|Collection username
     * @property Show\Field|Collection area_id
     * @property Show\Field|Collection city_id
     * @property Show\Field|Collection create_user_id
     * @property Show\Field|Collection province_id
     * @property Show\Field|Collection status
     * @property Show\Field|Collection connection
     * @property Show\Field|Collection exception
     * @property Show\Field|Collection failed_at
     * @property Show\Field|Collection payload
     * @property Show\Field|Collection queue
     * @property Show\Field|Collection class_data_id
     * @property Show\Field|Collection model_id
     * @property Show\Field|Collection model_type
     * @property Show\Field|Collection guard_name
     * @property Show\Field|Collection grade_id
     * @property Show\Field|Collection is_del
     * @property Show\Field|Collection plan_date
     * @property Show\Field|Collection plan_user
     * @property Show\Field|Collection remark
     * @property Show\Field|Collection year_class_id
     * @property Show\Field|Collection admin_user_id
     * @property Show\Field|Collection desc
     * @property Show\Field|Collection image
     * @property Show\Field|Collection notify_nums
     * @property Show\Field|Collection view_nums
     * @property Show\Field|Collection last_user_id
     * @property Show\Field|Collection birthday
     * @property Show\Field|Collection glasses_type
     * @property Show\Field|Collection id_card
     * @property Show\Field|Collection is_glasses
     * @property Show\Field|Collection is_myopia
     * @property Show\Field|Collection join_school_date
     * @property Show\Field|Collection l_axi
     * @property Show\Field|Collection l_axis
     * @property Show\Field|Collection l_cyl
     * @property Show\Field|Collection l_degree
     * @property Show\Field|Collection l_roc1
     * @property Show\Field|Collection l_roc2
     * @property Show\Field|Collection l_sph
     * @property Show\Field|Collection plan_id
     * @property Show\Field|Collection r_axi
     * @property Show\Field|Collection r_axis
     * @property Show\Field|Collection r_cyl
     * @property Show\Field|Collection r_degree
     * @property Show\Field|Collection r_roc1
     * @property Show\Field|Collection r_roc2
     * @property Show\Field|Collection r_sph
     * @property Show\Field|Collection sex
     * @property Show\Field|Collection student_code
     * @property Show\Field|Collection student_id
     * @property Show\Field|Collection plan_status
     * @property Show\Field|Collection email
     * @property Show\Field|Collection email_verified_at
     * @property Show\Field|Collection phone
     * @property Show\Field|Collection power_type
     * @property Show\Field|Collection power_user_id
     * @property Show\Field|Collection wx_user_id
     * @property Show\Field|Collection city
     * @property Show\Field|Collection country
     * @property Show\Field|Collection gender
     * @property Show\Field|Collection language
     * @property Show\Field|Collection nickname
     * @property Show\Field|Collection nums
     * @property Show\Field|Collection openid
     * @property Show\Field|Collection province
     * @property Show\Field|Collection session_key
     * @property Show\Field|Collection union_id
     * @property Show\Field|Collection url
     * @property Show\Field|Collection v_code
     *
     * @method Show\Field|Collection created_at(string $label = null)
     * @method Show\Field|Collection detail(string $label = null)
     * @method Show\Field|Collection id(string $label = null)
     * @method Show\Field|Collection name(string $label = null)
     * @method Show\Field|Collection type(string $label = null)
     * @method Show\Field|Collection updated_at(string $label = null)
     * @method Show\Field|Collection version(string $label = null)
     * @method Show\Field|Collection is_enabled(string $label = null)
     * @method Show\Field|Collection extension(string $label = null)
     * @method Show\Field|Collection icon(string $label = null)
     * @method Show\Field|Collection order(string $label = null)
     * @method Show\Field|Collection parent_id(string $label = null)
     * @method Show\Field|Collection uri(string $label = null)
     * @method Show\Field|Collection menu_id(string $label = null)
     * @method Show\Field|Collection permission_id(string $label = null)
     * @method Show\Field|Collection http_method(string $label = null)
     * @method Show\Field|Collection http_path(string $label = null)
     * @method Show\Field|Collection slug(string $label = null)
     * @method Show\Field|Collection role_id(string $label = null)
     * @method Show\Field|Collection user_id(string $label = null)
     * @method Show\Field|Collection value(string $label = null)
     * @method Show\Field|Collection avatar(string $label = null)
     * @method Show\Field|Collection password(string $label = null)
     * @method Show\Field|Collection remember_token(string $label = null)
     * @method Show\Field|Collection username(string $label = null)
     * @method Show\Field|Collection area_id(string $label = null)
     * @method Show\Field|Collection city_id(string $label = null)
     * @method Show\Field|Collection create_user_id(string $label = null)
     * @method Show\Field|Collection province_id(string $label = null)
     * @method Show\Field|Collection status(string $label = null)
     * @method Show\Field|Collection connection(string $label = null)
     * @method Show\Field|Collection exception(string $label = null)
     * @method Show\Field|Collection failed_at(string $label = null)
     * @method Show\Field|Collection payload(string $label = null)
     * @method Show\Field|Collection queue(string $label = null)
     * @method Show\Field|Collection class_data_id(string $label = null)
     * @method Show\Field|Collection model_id(string $label = null)
     * @method Show\Field|Collection model_type(string $label = null)
     * @method Show\Field|Collection guard_name(string $label = null)
     * @method Show\Field|Collection grade_id(string $label = null)
     * @method Show\Field|Collection is_del(string $label = null)
     * @method Show\Field|Collection plan_date(string $label = null)
     * @method Show\Field|Collection plan_user(string $label = null)
     * @method Show\Field|Collection remark(string $label = null)
     * @method Show\Field|Collection year_class_id(string $label = null)
     * @method Show\Field|Collection admin_user_id(string $label = null)
     * @method Show\Field|Collection desc(string $label = null)
     * @method Show\Field|Collection image(string $label = null)
     * @method Show\Field|Collection notify_nums(string $label = null)
     * @method Show\Field|Collection view_nums(string $label = null)
     * @method Show\Field|Collection last_user_id(string $label = null)
     * @method Show\Field|Collection birthday(string $label = null)
     * @method Show\Field|Collection glasses_type(string $label = null)
     * @method Show\Field|Collection id_card(string $label = null)
     * @method Show\Field|Collection is_glasses(string $label = null)
     * @method Show\Field|Collection is_myopia(string $label = null)
     * @method Show\Field|Collection join_school_date(string $label = null)
     * @method Show\Field|Collection l_axi(string $label = null)
     * @method Show\Field|Collection l_axis(string $label = null)
     * @method Show\Field|Collection l_cyl(string $label = null)
     * @method Show\Field|Collection l_degree(string $label = null)
     * @method Show\Field|Collection l_roc1(string $label = null)
     * @method Show\Field|Collection l_roc2(string $label = null)
     * @method Show\Field|Collection l_sph(string $label = null)
     * @method Show\Field|Collection plan_id(string $label = null)
     * @method Show\Field|Collection r_axi(string $label = null)
     * @method Show\Field|Collection r_axis(string $label = null)
     * @method Show\Field|Collection r_cyl(string $label = null)
     * @method Show\Field|Collection r_degree(string $label = null)
     * @method Show\Field|Collection r_roc1(string $label = null)
     * @method Show\Field|Collection r_roc2(string $label = null)
     * @method Show\Field|Collection r_sph(string $label = null)
     * @method Show\Field|Collection sex(string $label = null)
     * @method Show\Field|Collection student_code(string $label = null)
     * @method Show\Field|Collection student_id(string $label = null)
     * @method Show\Field|Collection plan_status(string $label = null)
     * @method Show\Field|Collection email(string $label = null)
     * @method Show\Field|Collection email_verified_at(string $label = null)
     * @method Show\Field|Collection phone(string $label = null)
     * @method Show\Field|Collection power_type(string $label = null)
     * @method Show\Field|Collection power_user_id(string $label = null)
     * @method Show\Field|Collection wx_user_id(string $label = null)
     * @method Show\Field|Collection city(string $label = null)
     * @method Show\Field|Collection country(string $label = null)
     * @method Show\Field|Collection gender(string $label = null)
     * @method Show\Field|Collection language(string $label = null)
     * @method Show\Field|Collection nickname(string $label = null)
     * @method Show\Field|Collection nums(string $label = null)
     * @method Show\Field|Collection openid(string $label = null)
     * @method Show\Field|Collection province(string $label = null)
     * @method Show\Field|Collection session_key(string $label = null)
     * @method Show\Field|Collection union_id(string $label = null)
     * @method Show\Field|Collection url(string $label = null)
     * @method Show\Field|Collection v_code(string $label = null)
     */
    class Show {}

    /**
     
     */
    class Form {}

}

namespace Dcat\Admin\Grid {
    /**
     
     */
    class Column {}

    /**
     
     */
    class Filter {}
}

namespace Dcat\Admin\Show {
    /**
     
     */
    class Field {}
}
