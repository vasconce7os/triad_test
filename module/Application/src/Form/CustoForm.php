<?php

namespace Application\Form;

use Zend\Form\Form;

class CustoForm extends Form
{
    public function __construct($name = null)
    {
        // We will ignore the name provided to the constructor
        parent::__construct('carriers');

        $this->add([
            'name' => 'id',
            'type' => 'hidden',
        ]);
        $this->add([
            'name'    => 'name',
            'type'    => 'text',
            'options' => [
                'label' => 'Nome da transportadora',
            ],
            'attributes' => [
                'readonly' => 'readonly',
            ],
        ]);
        $this->add([
            'name'    => 'city',
            'type'    => 'text',
            'options' => [
                'label' => 'Cidade da trasportadora',
            ],
            'attributes' => [
                'readonly' => 'readonly',
            ],
        ]);


        $this->add([
            'name'    => 'freight_air',
            'type'    => 'Number',
            'options' => [
                'label' => 'Frete aereo',
            ],
        ]);
        $this->add([
            'name'    => 'freight_water',
            'type'    => 'Number',
            'options' => [
                'label' => 'Frete aquatio',
            ],
        ]);
        $this->add([
            'name'    => 'freight_earthly',
            'type'    => 'Number',
            'options' => [
                'label' => 'Frete terrestre',
            ],
        ]);

        $this->add([
            'name'       => 'submit',
            'type'       => 'submit',
            'attributes' => [
                'value' => 'Inserir',
                'id'    => 'submitbutton',
            ],
        ]);
    }
}
