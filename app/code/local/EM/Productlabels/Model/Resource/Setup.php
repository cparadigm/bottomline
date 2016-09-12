<?php
class EM_Productlabels_Model_Resource_Setup extends Mage_Eav_Model_Entity_Setup
{
     /**
     * Retreive default entities: productlabels
     *
     * @return array
     */
    public function getDefaultEntities()
    {
        $entities = array(
            EM_Productlabels_Model_Productlabels::ENTITY    => array(
                'entity_model'            => 'productlabels/productlabels',
				'attribute_model'		  => 'productlabels/attribute',
                'table'                          => 'productlabels/productlabels',
				'additional_attribute_table'     => 'productlabels/eav_attribute',
				'entity_attribute_collection'    => 'productlabels/attribute_collection',
				'default_group'                  => 'General Information',
                'attributes'                     => array(
                    'name'          => array(
                        'type'               => 'varchar',
                        'label'              => 'Label Name',
                        'input'              => 'text',
                        'required'           => true,
						'global'             => EM_Productlabels_Model_Attribute::SCOPE_STORE,
                        'sort_order'         => 1
                    ),
					'image'          => array(
                        'type'               => 'varchar',
                        'label'              => 'Image',
                        'input'              => 'image',
                        'backend'            => 'productlabels/productlabels_attribute_backend_image',
                        'required'           => false,
                        'sort_order'         => 2,
                        'global'             => EM_Productlabels_Model_Attribute::SCOPE_STORE
                    ),
					'background'          => array(
                        'type'               => 'varchar',
                        'label'              => 'Background',
                        'input'              => 'image',
                        'backend'            => 'productlabels/productlabels_attribute_backend_image',
                        'required'           => false,
                        'sort_order'         => 3,
                        'global'             => EM_Productlabels_Model_Attribute::SCOPE_STORE
                    ),
                    'texthtml' 		=> array(
                        'type'               => 'text',
                        'label'              => 'Text',
                        'input'              => 'textarea',
						'global'             => EM_Productlabels_Model_Attribute::SCOPE_STORE,
                        'required'           => false,
                        'sort_order'         => 4                        
                    ),
					'css_class'          => array(
                        'type'               => 'varchar',
                        'label'              => 'Css class',
                        'input'              => 'text',
                        'required'           => false,
						'note'               => 'The name of often used css.(Bestseller Product Label: bestseller; New Product Label: new; Sale Product Label: special)',
						'global'             => EM_Productlabels_Model_Attribute::SCOPE_STORE,
                        'sort_order'         => 5
                    ),
					'status'         => array(
                        'type'               => 'int',
                        'label'              => 'Enable',
                        'input'              => 'select',
						'source'             => 'eav/entity_attribute_source_boolean',
						'global'             => EM_Productlabels_Model_Attribute::SCOPE_STORE,
                        'required'           => true,
                        'sort_order'         => 7
                    ),
					'actions'         => array(
                        'type'               => 'text',
                        'label'              => 'Actions',
                        'input'              => 'textarea',
						'global'             => EM_Productlabels_Model_Attribute::SCOPE_GLOBAL,
                        'required'           => false,
                        'sort_order'         => 8,
						'visible'            => false
                    ),
                    'created_at'         => array(
                        'type'               => 'static',
                        'input'              => 'text',
                        'backend'            => 'eav/entity_attribute_backend_time_created',
                        'sort_order'         => 9,
                        'visible'            => false
                    ),
                    'updated_at'         => array(
                        'type'               => 'static',
                        'input'              => 'text',
                        'backend'            => 'eav/entity_attribute_backend_time_updated',
                        'sort_order'         => 10,
                        'visible'            => false
                    )					
                )
            ),
			EM_Productlabels_Model_Css::ENTITY    => array(
                'entity_model'            => 'productlabels/css',
				'attribute_model'		  => 'productlabels/attribute',
                'table'                          => 'productlabels/css',
				'additional_attribute_table'     => 'productlabels/eav_attribute',
				'entity_attribute_collection'    => 'productlabels/attribute_collection',
				'default_group'                  => 'General Information',
                'attributes'                     => array(
                    'content'          => array(
                        'type'               => 'text',
                        'label'              => 'Css Content',
                        'input'              => 'textarea',
                        'required'           => false,
						'global'             => EM_Productlabels_Model_Attribute::SCOPE_STORE,
                        'sort_order'         => 1
                    )	
                )
            )
        );
        return $entities;
    }
}
