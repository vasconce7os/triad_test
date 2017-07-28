<?php

namespace Application\Form;

use Zend\Form\Form;

class CalculoFreteForm extends Form
{
    public function __construct($name = null)
    {
        // We will ignore the name provided to the constructor
        parent::__construct('calcular_frete');

        $this->add([
            'name' => 'id',
            'type' => 'hidden',
        ]);
        $this->add([
            'name'    => 'products_qde',
            'type'    => 'Number',
            'options' => [
                'label' => 'Quantidade de produtos',
            ],
            'attributes'=> [
                'min'  => 1,
                'max'  => 88888,
                'step' => 1,
            ],
        ]);

        $this->add(array(
            'type'=> 'Zend\Form\Element\Select',
            'name' => 'transport_type',
            'options' => array(
                'label' => 'Tipo de transporte/frete',
                'options' =>
                array(
                    array
                    (
                        'value' => '0',
                        'label' => 'Selecione',
                        'disabled' => 'disabled',
                        'selected'=> 'selected',
                        'class'=> 'red'
                    ),
 
                    'freight_earthly' => 'Terrestre',
                    'freight_air' => 'Aereo',
                    'freight_water'=> 'Aquaviario',
                )
            ),
            'attributes' => array(
                // 'class' => 'select-all-half select-smallscreen-full'
            )
        ));

        $this->add(
            array(
            'type'=> 'hidden',
                'name' => 'transport_id',
                'options' => array(
                    // 'label' => '',
                )
            )
        );
        $this->add(
            array(
            'type'=> 'hidden',
                'name' => 'products_id',
                'options' => array(
                )
            )
        );

        $this->add([
            'name'       => 'submit',
            'type'       => 'submit',
            'attributes' => [
                'value' => 'Calcular',
                'id'    => 'submitbutton',
            ],
        ]);
    }
}
