<?php

namespace Application\Form;

use Zend\Form\Form;

class TransportadoraForm extends Form
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
                'label' => 'Nome',
            ],
        ]);
        $this->add([
            'name'    => 'city',
            'type'    => 'text',
            'options' => [
                'label' => 'Cidade',
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
