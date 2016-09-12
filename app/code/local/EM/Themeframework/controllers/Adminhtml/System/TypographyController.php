<?php
class EM_Themeframework_Adminhtml_System_TypographyController extends Mage_Adminhtml_Controller_Action
{
    /**
     * WYSIWYG Plugin Action
     *
     */
    public function wysiwygPluginAction()
    {
        $response = array(
            'list'  =>  $this->getListTypos(),
            'general'  =>  $this->getGeneralConfig()
        );
        $this->getResponse()->setBody(Zend_Json::encode($response));
    }

    /**
     * Get general config
     *
     * @return array
     */
    public function getGeneralConfig(){
        $helper = Mage::helper('themeframework');
        return array(
            'class_list'    =>  array(
                'label'     =>  $helper->__('General Classes'),
                'values_option'    =>  array(
                    'bottom'        =>  $helper->__('This block always has margin-bottom = 0.'),
                    'hide-lte2'     =>  $helper->__('This block will disappear when resize window less than 1280px.'),
                    'hide-lte1'     =>  $helper->__('This block will disappear when resize window less than 980px.'),
                    'hide-lte0'     =>  $helper->__('This block will disappear when resize window less than 760px.')
                )
            ),
            'global_messages'=> array(
                'required'  =>  $helper->__('You have to fill all required fields')
            )
        );
    }

    /**
     * Get list of typography
     *
     * @return array
     */
    public function getListTypos(){
        $helper = Mage::helper('themeframework');
        $types = array(
            array(
                'title'     =>  $helper->__('Heading tag'),
                'type'      =>  'heading',
                'id_area'   =>  'heading-'.rand(0,90),
                'tag'       =>  'heading',
				'conf'  =>  array(
                    'frontend_input' => 'heading',
                    'params' => array(
						'label'	=>	$helper->__('Tag content'),
                        'class_option'     =>  array(
							'label'	=>	$helper->__('Type'),
							'values'=>	array(
								'h1' => $helper->__('H1'),
								'h2' => $helper->__('H2'),
								'h3' => $helper->__('H3'),
								'h4' => $helper->__('H4'),
								'h5' => $helper->__('H5'),
								'h6' => $helper->__('H6')
							)
						)
                    )
                )
            ),
			array(
                'title' =>  $helper->__('Unorder list'),
                'type'  =>  'ul',
                'tag'       =>  'ul',
                'id_area'   =>  'ul-'.rand(0,90),
                'conf'  =>  array(
                    'frontend_input' => 'list',
                    'params' => array(
                        'button_add' => $helper->__('Add item'),
                        'insert' => 'ListTypo.insert',
                        'title_item' => $helper->__('Content item'),
                        'class_option'     =>  array(
							'label'	=>	$helper->__('Direction'),
							'values'=>	array('' => $helper->__('Vertical'),'hoz' => $helper->__('Horizontal'))
						)
                    )
                )
            ),
            array(
                'title' =>  $helper->__('Order list'),
                'type'  =>  'ol',
                'tag'       =>  'ol',
                'id_area'   =>  'ol-'.rand(0,90),
                'conf'  =>  array(
                    'frontend_input' => 'list',
                    'params' => array(
                        'button_add' => $helper->__('Add item'),
                        'insert' => 'ListTypo.insert',
                        'title_item' => $helper->__('Content item'),
						'class_option'     =>  array(
							'label'	=>	$helper->__('Direction'),
							'values'=>	array('' => $helper->__('Vertical'),'hoz' => $helper->__('Horizontal'))
						)
                    )
                )
            ),
			array(
                'title' =>  $helper->__('Definition'),
                'type'  =>  'dl',
                'tag'       =>  'dl',
                'id_area'   =>  'dl-'.rand(0,90),
                'conf'  =>  array(
                                'frontend_input' => 'dl',
                                'params' => array(
                                    'button_add' => $helper->__('Add new definition'),
                                    'insert' => 'DlTypo.insert',
                                    'title_dt' => $helper->__('Definition title'),
                                    'title_dd' => $helper->__('Definition content')
                                )
                            )
            ),	
			array(
                'title'     =>  $helper->__('Code'),
                'type'      =>  'code',
                'id_area'   =>  'code-'.rand(0,90),
                'conf'      =>  array('frontend_input' => 'textarea','params' => array('label'=>$helper->__('Tag content'))),
                'tag'       =>  'code'
            ),
			array(
                'title'     =>  $helper->__('Block Quote'),
                'type'      =>  'blockquote',
                'id_area'   =>  'blockquote-'.rand(0,90),
                'conf'      =>  array('frontend_input' => 'text','params' => array('label'=>$helper->__('Quote content'))),
                'tag'       =>  'blockquote'
            ),
			array(
                'title'     =>  $helper->__('Box'),
                'type'      =>  'box',
                'tag'       =>  'div',
                'id_area'   =>  'box-f-left-'.rand(0,90),
                'conf'      =>  array(
                                    'frontend_input' => 'textarea',
                                    'params'    =>  array(
                                        'class_text'    =>  'box',
										'class_option'     =>  array(
											'label'	=>	$helper->__('Position'),
											'values'=>	array(
												'f-left' => $helper->__('Box with left position'),
												'f-right' => $helper->__('Box with right position')
											)
										)
                                    )
                                )
            ),
			array(
                'title'     =>  $helper->__('Table'),
                'type'      =>  'table',
                'tag'       =>  'table',
                'id_area'   =>  'table-'.rand(0,90),
                'conf'      =>  array(
                                    'frontend_input' => 'table',
                                    'params' => array(
                                        'class_text' => 'data-table',
                                        'num_col_label'   =>  $helper->__('The number of column'),
                                        'num_row_label'   =>  $helper->__('The number of row'),
                                        'btn_generate_label'   =>  $helper->__('Generate table'),
                                        'alert_text'           =>  $helper->__('Enter number of row and column'),
                                        'remove_thead_label'   =>  $helper->__('Remove thead'),
                                        'remove_thead'      =>  array('no' => $helper->__('No'),'yes' => $helper->__('Yes'))
                                    )
                                )
            ),
            array(
                'title'     =>  $helper->__('Small tag'),
                'type'      =>  'tag_small',
                'id_area'   =>  'tag_small-'.rand(0,90),
                'conf'      =>  array('frontend_input' => 'text','params'=>array('class_text' => 'small','label'=>$helper->__('Tag content'))),
                'tag'       =>  'p'
            ),
            array(
                'title'     =>  $helper->__('Element with underline'),
                'type'      =>  'e_underline',
                'id_area'   =>  'e_underline-'.rand(0,90),
                'conf'      =>  array('frontend_input' => 'text','params' => array('class_text' => 'underline','label'=>$helper->__('Tag content'))),
                'tag'       =>  'p'
            ),
            array(
                'title'     =>  $helper->__('Select icon, brand'),
                'type'      =>  'icon',
                'tag'       =>  'span',
                'id_area'   =>  'icon-'.rand(0,90),
                'conf'      =>  array(
                                    'frontend_input' => 'icon',
                                    'params' => array(
										'class_option'     =>  array(
											'label'	=>	$helper->__('Type'),
											'values'=>	array(
												'icon facebook'	=>	$helper->__('Icon Facebook'),
												'icon twitter'	=>	$helper->__('Icon Twitter'),
												'icon flickr'	=>	$helper->__('Icon Flickr'),
												'icon rss'	=>	$helper->__('Icon Rss'),
												'icon visa'	=>	$helper->__('Icon Visa'),
												'icon mastercard'	=>	$helper->__('Icon Mastercard'),
												'icon paypal'	=>	$helper->__('Icon Paypal'),
												'icon express'	=>	$helper->__('Icon Express'),
												'brand-logo chanel'	=>	$helper->__('Logo Channel'),
												'brand-logo puma'	=>	$helper->__('Icon Puma'),
												'brand-logo versace'	=>	$helper->__('Logo Versace'),
												'brand-logo lacoste'	=>	$helper->__('Logo Lacoste'),
												'brand-logo levis'	=>	$helper->__('Logo Levis'),
												'brand-logo adidas'	=>	$helper->__('Logo Adidas'),
											)
										)
                                    )
                                )
            ),
			array(
                'title'     =>  $helper->__('Fluid Image'),
                'type'      =>  'img',
                'tag'       =>  'img',
                'id_area'   =>  'fluid-'.rand(0,90),
                'conf'      =>  array(
                                    'frontend_input' => 'text',
                                    'params'    =>  array(
                                        'class_text'    =>  'fluid',
										'label'			=>	$helper->__('Url')
                                    )
                                )
            )
        );
        $config = new Varien_Object();
        $config->setData('list',$types);
        Mage::dispatchEvent('prepare_typo_list',array('config' => $config));
        return $config->getData('list');
    }

    /**
     * Get configuartion list of typography
     *
     * @return array
     */
    public function getConfTypo(){
        $helper = Mage::helper('themeframework');
        $conf = array(
            'h1'            => array('tag' => 'h1','frontend_input' => 'text'),
            'ul'            => array('tag' => 'li','frontend_input' => 'list','params' => array('button_add' => $helper->__('Add item'), 'insert' => 'List.insert')),
            'dl'            => array(
                                     'tag' =>  array('dt,dd'),
                                     'frontend_input' => 'dl',
                                     'params' => array(
                                            'button_add' => $helper->__('Add item'),
                                            'insert' => 'DlTypo.insert',
                                            'title_dt' => $helper->__('Definition title'),
                                            'title_dd' => $helper->__('Definition content')
                                     )
                               ),
            'box-f-left'    => array('tag' => 'text','class' => 'box f-left','frontend_input' => 'textarea'),
            'table'         => array(
                                    'tag' => array(
                                        'thead' => array(
                                            'tag' => 'th',
                                            'button_add' => $helper->__('Add column')
                                        ),
                                        'tbody' => array(
                                            'tag' => 'tr',
                                            'button_add' => $helper->__('Add row')
                                        )
                                    ),
                                    'frontend_input' => 'table'
                               )
        );
        return $conf;
    }

}