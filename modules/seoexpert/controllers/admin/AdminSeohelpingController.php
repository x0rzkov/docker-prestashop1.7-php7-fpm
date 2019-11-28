<?php
/**
* 2007-2017 PrestaShop
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
* @author    PrestaShop SA <contact@prestashop.com>
* @copyright 2007-2017 PrestaShop SA
* @license   http://addons.prestashop.com/en/content/12-terms-and-conditions-of-use
* International Registered Trademark & Property of PrestaShop SA
*/

class AdminSeohelpingController extends ModuleAdminController
{
    /** @var protected array cache filled with lang informations */
    protected static $rc;
    protected static $social_rule_cache;
    protected static $products;

    /**
    * Load the HTML form in the modalbox
    *
    * @access see JS
    * @param int $id_rule
    * @param string $role
    * @param string $type
    * @return html
    */
    public function ajaxProcessLoadForm()
    {
        $id_objet = (int)trim(pSQL(Tools::getValue('id_rule')));
        $role = trim(pSQL(Tools::getValue('role')));
        $type = trim(pSQL(Tools::getValue('type')));
        exit($this->module->loadForm($id_objet, $role, $type));
    }

    /**
    * Delete all the rules !
    *
    * @access see JS
    * @return string
    */
    public function ajaxProcessCleanUp()
    {
        if (!$this->module->active || !_PS_MODE_DEV_) {
            return;
        }

        if (version_compare(_PS_VERSION_, '1.6', '>') && _PS_CACHE_ENABLED_) {
            Cache::getInstance()->deleteCacheDirectory();
        }

        $rules = Db::getInstance()->Execute('TRUNCATE TABLE `'._DB_PREFIX_.bqSQL(SeoExpert::$rules_table).'`');
        $objects = Db::getInstance()->Execute('TRUNCATE TABLE `'._DB_PREFIX_.bqSQL(SeoExpert::$objects_table).'`');
        $patterns = Db::getInstance()->Execute('TRUNCATE TABLE `'._DB_PREFIX_.bqSQL(SeoExpert::$patterns_table).'`');
        if (!$rules || !$objects || !$patterns) {
            exit('1');
        }

        exit('0');
    }

    /**
    * Load detail of a rule
    *
    * @access see JS
    * @param int $id_cat
    * @param string $type
    * @return html
    */
    public function ajaxProcessRuleDetails()
    {
        $id_cat = (int)trim(Tools::getValue('id_cat'));
        $type = trim(pSQL(Tools::getValue('type')));
        exit($this->module->loadRuleDetails($id_cat, $type));
    }

    /**
    * Get all rules already create
    *
    * @access see JS
    * @param string $role
    * @param string $type
    * @return html
    */
    public function ajaxProcessGetHistory()
    {
        $role = trim(pSQL(Tools::getValue('role')));
        $type = trim(pSQL(Tools::getValue('type')));
        exit($this->module->getHistory($type, $role));
    }

    /**
    * Switch rule status
    *
    * @access see JS
    * @param int $id_rule
    * @return int
    */
    public function ajaxProcessSwitchAction()
    {
        $id_rule = (int)trim(Tools::getValue('id_rule'));
        exit($this->module->switchAction($id_rule));
    }

    /**
    * Save all the rule
    *
    * @access see JS
    * @param array $params
    * @param string $role
    * @param string $type
    * @param int $apply
    * @param int $id_rule
    * @return html
    */
    public function ajaxProcessSaveRules()
    {
        $metas = array();
        $params = Tools::getValue('params');
        $role = trim(pSQL(Tools::getValue('role')));
        $type = trim(pSQL(Tools::getValue('type')));
        $apply = (int)trim(pSQL(Tools::getValue('apply')));
        $id_rule = (int)trim(pSQL(Tools::getValue('id_rule')));

        if (!empty($params)) {
            $count_feat = 1;
            foreach ($params as &$param) {
                $name = trim($param['name']);
                $is_fb = stristr($name, 'fb');
                $is_tw = stristr($name, 'tw');
                $value = trim($param['value']);
                $is_meta = stristr($name, 'meta');
                $is_lang = stristr($name, 'select');
                $is_name = stristr($name, 'rule_');
                $is_cat = stristr($name, 'category');
                $is_link = stristr($name, 'link');
                $is_feat = stristr($name, '[]');

                if ($is_lang !== false && !empty($value)) {
                    $id_lang = (int)$value;
                } elseif ($is_name !== false) {
                    $rule_name = trim(pSQL($value));
                } elseif ($is_cat !== false) {
                    $categories = explode(',', $value);
                } elseif (($is_meta !== false || $is_fb !== false || $is_tw !== false) && !Tools::isEmpty($value)) {
                    if ($is_feat !== false) {
                        $metas[] = array(
                            'tw_data_'.$count_feat => trim(pSQL($value))
                        );
                        $count_feat++;
                    } else {
                        $metas[] = array(
                            trim(pSQL(pSQL($name))) => trim(pSQL($value))
                        );
                    }
                } elseif ($is_link !== false && !Tools::isEmpty($value)) {
                    $metas[] = array(
                        trim(pSQL($name)) => trim(pSQL($value))
                    );
                }
            }
            unset($params, $param);

            $save_rule = array(
                'id_lang' => $id_lang,
                'id_shop' => (int)$this->context->shop->id,
                'name' => $rule_name,
                'type' => $type,
                'role' => $role,
                'active' => 1,
                'date_add' => date('Y-m-d H:i:s')
            );

            if ($apply === 1) {
                $save_rule['date_upd'] = date('Y-m-d H:i:s');
            }

            // Check param's
            if (!empty($save_rule) && !empty($metas) && (isset($categories[0]) && !Tools::isEmpty($categories[0]))) {
                if ($id_rule === 0) {
                    $id_rule = $this->module->save(SeoExpert::$rules_table, $save_rule);
                } else {
                    $data = array(
                        'name' => $rule_name,
                        'id_rule' => (int)$id_rule
                    );
                    $this->module->update(SeoExpert::$rules_table, $data);
                    TinyCache::clearCache('rule_cache_'.$id_rule);
                    TinyCache::clearCache('cpt_rule_'.$id_rule);
                }

                if (!empty($id_rule) && !empty($metas)) {
                    $this->module->delete($id_rule, SeoExpert::$patterns_table);
                    foreach ($metas as &$meta) {
                        foreach ($meta as $key => $value) {
                            $insert_pattern = array(
                                'id_rule' => $id_rule,
                                'field' => $key,
                                'pattern' => $value,
                            );
                            $this->module->saveObj(SeoExpert::$patterns_table, $insert_pattern);
                        }
                        unset($meta, $key, $value, $insert_pattern);
                    }
                    unset($metas);

                    $this->module->delete($id_rule, SeoExpert::$objects_table);
                    foreach ($categories as &$cat) {
                        $insert_category = array(
                            'id_rule' => $id_rule,
                            'id_obj' => $cat
                        );
                        $this->module->saveObj(SeoExpert::$objects_table, $insert_category);
                    }
                    unset($categories, $cat, $insert_category);

                    exit($this->module->displayConfirmation('Your SEO rule has been saved successfully'));
                } else {
                    exit($this->module->displayError('An error occurred while creating the rule'));
                }
            } else {
                exit($this->module->displayError('An error occurred while creating the rule'));
            }
        }
    }

    /**
    * Delete all rows from one rule
    *
    * @access see JS
    * @param int $id
    * @return html
    */
    public function ajaxProcessDeleteRules()
    {
        $id_objet = (int)trim(pSQL(Tools::getValue('id')));
        $this->module->delete($id_objet);

        exit($this->module->displayConfirmation('Your SEO rule has been deleted successfully'));
    }

    /**
    * Check if it's the default rule
    *
    * @access see JS
    * @param int $id_lang
    * @param int $id_rule
    * @param string $role
    * @param string $type
    * @return bool
    */
    public function ajaxProcessDefaultRule()
    {
        $id_lang = (int)trim(pSQL(Tools::getValue('id_lang')));
        $id_objet = (int)trim(pSQL(Tools::getValue('id_rule')));
        $role = trim(pSQL(Tools::getValue('role')));
        $type = trim(pSQL(Tools::getValue('type')));
        $is_default_rule = (bool)$this->module->isDefaultRule($id_lang, $id_objet, $role, $type);
        exit("$is_default_rule");
    }

    /**
    * Reload DOM after performing an action
    * see (http://legacy.datatables.net/usage/server-side)
    *
    * @access see JS
    * @param string $role
    * @param string $type
    * @param string $sEcho
    * @param string $sSearch
    * @param string $iSortCol_0
    * @param string $iSortingCols
    * @param string $iDisplayStart
    * @param string $iDisplayLength
    * @return json
    */
    public function ajaxProcessReloadData()
    {
        $filter = $order = $limit = '';
        $role = trim(pSQL(Tools::getValue('role')));
        $type = trim(pSQL(Tools::getValue('type')));
        $echo = (int)trim(pSQL(Tools::getValue('sEcho')));
        $search = trim(pSQL(Tools::getValue('sSearch')));
        $sort_col = (int)trim(pSQL(Tools::getValue('iSortCol_0')));
        $sorting_cols = (int)trim(pSQL(Tools::getValue('iSortingCols')));
        $display_start = (int)trim(pSQL(Tools::getValue('iDisplayStart')));
        $display_length = (int)trim(pSQL(Tools::getValue('iDisplayLength')));
        $columns = array('msr.id_rule', 'l.name', 's.name', 'msr.active', 'msr.date_upd');
        $count_columns = count($columns);

        /* search column filtering */
        if (isset($search) && !empty($search)) {
            $filter = 'AND (';
            for ($i = 0; $i < $count_columns; $i++) {
                $filter .= $columns[$i]." LIKE '%".$search."%' OR ";
            }
            $filter = substr_replace($filter, '', -3);
            $filter .= ')';
        }

        /* Individual column filtering */
        for ($i = 0; $i < $count_columns; $i++) {
            $search_x = trim(pSQL(Tools::getValue('search_'.$i)));
            $searchable_x = trim(pSQL(Tools::getValue('bSearchable_'.$i)));
            if (isset($searchable_x) && $searchable_x === 'true' && $search_x !== '') {
                $filter .= ' AND '.$columns[$i]." LIKE '%".$search_x."%' ";
            }
        }

        /* Order column filtering */
        if (isset($sort_col)) {
            $order = 'ORDER BY ';
            for ($i = 0; $i < $sorting_cols; $i++) {
                $sort_dir_x = trim(pSQL(Tools::getValue('sSortDir_'.$i)));
                $sort_col_x = trim(pSQL(Tools::getValue('iSortCol_'.$i)));
                if ($sort_col_x) {
                    $order .= $columns[$sort_col_x - 1].' '.($sort_dir_x === 'asc' ? 'ASC' : 'DESC').', ';
                }
            }

            $order = substr_replace($order, '', -2);
            if (trim($order) === 'ORDER BY') {
                $order = '';
            }
        }

        /* Set limit */
        if (isset($display_start) && $display_length !== -1) {
            $limit = ' LIMIT '.$display_start.', '.$display_length;
        }

        $results = $this->module->getHistory($type, $role, $filter, $order, $limit);
        $total_record = $this->module->countRules($type);
        $filtered_total = count($results);

        $data = array();
        if (Shop::isFeatureActive()) {
            $columns = array('name', 'lang', 'shop', 'nb_obj', 'active', 'date_upd');
        } else {
            $columns = array('name', 'lang', 'nb_obj', 'active', 'date_upd');
        }

        foreach ($results as &$result) {
            $row = array();
            $row[] = $this->module->getIcon('plus');
            foreach ($result as $key => $value) {
                if ($key === 'id_lang') {
                    $id_lang = (int)$value;
                }
                if (in_array($key, $columns)) {
                    if ($key === 'nb_obj' && $value === 'All') {
                        $row[] = $this->l('All categories');
                    } elseif ($key === 'lang') {
                        $row[] = $this->module->getIcon('flag', $id_lang);
                    } elseif ($key === 'active') {
                        $row[] = $this->module->loadStatus($value);
                    } elseif ($key === 'date_upd') {
                        $row[] = !empty($value) ? SeoTools::displayDate($value, $id_lang): 'N/A';
                    } else {
                        $row[] = $value;
                    }
                }
                if ($key === 'id_rule') {
                    $row['DT_RowId'] = 'cat_'.$value;
                }
            }
            unset($key, $value);
            $row[] = $this->module->loadActions($result, $type, $role);
            $data[] = $row;
        }
        unset($result, $results);

        $output = array(
            'sEcho' => $echo,
            'iTotalRecords' => $total_record,
            'iTotalDisplayRecords' => $filtered_total,
            'aaData' => $data
        );
        exit(Tools::jsonEncode($output));
    }

    /**
    * Read counter cache
    *
    * @param int $id_category
    * @return json
    */
    public function ajaxProcessGetProgress()
    {
        $result = array();
        $id_rule = (int)trim(pSQL(Tools::getValue('id_rule')));
        $result['value'] = (int)TinyCache::getCache('cpt_rule_'.$id_rule);
        echo Tools::jsonEncode($result);
    }

    /**
    * Remove counter cache
    *
    * @param int $id_category
    */
    public function ajaxProcessResetCounter()
    {
        $id_rule = (int)trim(pSQL(Tools::getValue('id_rule')));
        TinyCache::clearCache('cpt_rule_'.$id_rule);
        echo Tools::jsonEncode(false);
    }

    /**
    * Applies a rule
    *
    * @param int $id_category
    * @return html
    */
    public function ajaxProcessGenerateRule()
    {
        $id_rule = (int)trim(pSQL(Tools::getValue('id_rule')));

        self::$rc = TinyCache::getCache('rule_cache_'.$id_rule);
        if (self::$rc === null || empty(self::$rc)) {
            /* Get all rules informations */
            self::$rc = SeoTools::mergeRecursive($this->module->getPatternsRule($id_rule));
            TinyCache::setCache('rule_cache_'.$id_rule, self::$rc);
        }

        $limit = 1000;
        $id_lang = (int)self::$rc['id_lang'];
        $page = (int)trim(pSQL(Tools::getValue('page', 1)));
        $nb_prod = (int)trim(pSQL(Tools::getValue('batch', 0)));

        $id_employee = isset($this->context->employee) ? $this->context->employee->id : '';
        $id_shop = isset(self::$rc['id_shop']) ? (int)self::$rc['id_shop'] : $this->context->shop->id;

        Cache::store('hook_module_exec_list_'.$id_shop.$id_employee, array());

        if (!empty(self::$rc)) {
            $id_category = '';
            $error = array();
            $get_obj = $this->module->getObjectsRule($id_rule);
            foreach ($get_obj as &$obj) {
                $id_category .= (int)$obj['id_obj'].', ';
            }
            unset($get_obj, $obj);
            $id_category = Tools::substr($id_category, 0, -2);

            $message = '<b>'.$this->l('Rule').': '.$this->module->getRuleName($id_rule).'</b><br />';
            $products = SeoTools::getProducts($id_lang, $page, $limit, 'id_product', 'ASC', $id_category);
            $max_pages = SeoTools::getMaxPages($limit);
            if ($page > $max_pages['count']) {
                $page = $max_pages['count'];
            }

            // Fix in case of FOUND_ROWS return 0 but we have product...
            $count_prod = count($products);
            if ($max_pages['max_result'] == 0 && $count_prod > 0) {
                $max_pages['max_result'] = $count_prod;
            }

            self::$products[$id_rule] = $products;

            if ($max_pages['max_result'] > 0) {
                foreach (self::$products[$id_rule] as &$row) {
                    $id = (int)$row['id_product'];
                    $generate = $this->module->generate($id, self::$rc['pattern'], (int)$id_shop, (int)$id_lang);
                    if (!empty($generate) && $generate !== 1) {
                        $error[(int)$row['id_product']] = $generate;
                    } else {
                        $nb_prod++;
                    }
                    $calc = round((($nb_prod / $max_pages['max_result']) * 100), 2);
                }
                unset($row);

                if ($page === (int)$max_pages['count']) {
                    $message .= $nb_prod .'/'.$max_pages['max_result'].' ';
                    $message .= $this->l('product(s) have been updated').'.<br />';
                    if (!empty($error)) {
                        $message .= '<br />'.count($error).' '.$this->l('error(s) encountered:').'<br />';
                        foreach ($error as $key => $value) {
                            $message .= $value.' ('.$key.')<br />';
                        }
                        unset($error, $key, $value);
                    }
                }
            } else {
                $calc = 100;
                $str = "Your products don't have targeted category as default category in this rule";
                $message .= $this->l($str).'.<br />';
                $url = "doc.prestashop.com/display/";
                $url .= "PS16/Managing+Products#ManagingProducts-ManagingtheProduct\'sAssociations";
                $message .= '<a href="'.$url.'">'.$this->l("See Managing the Product's Associations").'.</a><br />';
            }


            $this->module->updateApply($id_rule);

            Cache::clean('hook_module_exec_list_*');

            die(Tools::jsonEncode(array(
                'page' => $page,
                'pourcent' => $calc,
                'message' => $message,
                'batch' => $nb_prod,
                'max_pages' => $max_pages['count'],
                'max_result' => $max_pages['max_result'],
            )));
        } else {
            // Social Rule: No need to update products
            TinyCache::setCache('dfsfdsdf_'.$id_rule, 100);
            self::$social_rule_cache = TinyCache::getCache('social_rule_cache_'.$id_rule);
            if (self::$social_rule_cache === null || empty(self::$social_rule_cache)) {
                /* Get all rules informations */
                self::$social_rule_cache = SeoTools::mergeRecursive($this->module->getSocialPatternsRule($id_rule));
                TinyCache::setCache('social_rule_cache_'.$id_rule, self::$social_rule_cache);
            }

            if (self::$social_rule_cache) {
                $max_pages = 1;

                echo '<b>'.$this->l('Rule').': '.$this->module->getRuleName($id_rule).'</b> ';

                $this->module->updateApply($id_rule);

                Cache::clean('hook_module_exec_list_*');

                die(Tools::jsonEncode(array(
                    'pourcent' => 100,
                    'message' => $this->l('All the products have been updated'),
                    'batch' => 1,
                    'page' => $max_pages,
                    'max_pages' => $max_pages,
                    'max_result' => $max_pages,
                )));
            }
        }
    }
}
