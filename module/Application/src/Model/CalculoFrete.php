<?php

namespace Application\Model;

use DomainException;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class CalculoFrete
{
    public  $id;

    public  $products_qde;
    public  $products_id;

    public  $transport_type;
    public  $transport_id;

    private $inputFilter;

    public function exchangeArray(array $data)
    {
        $this->id     = (!empty($data['id'])) ? $data['id'] : null;
        $this->products_qde = (!empty($data['products_qde'])) ? $data['products_qde'] : null;
        $this->products_id = (!empty($data['products_id'])) ? $data['products_id'] : null;
        $this->transport_type  = (!empty($data['transport_type'])) ? $data['transport_type'] : null;
        $this->transport_id  = (!empty($data['transport_id'])) ? $data['transport_id'] : null;
    }

    public function getArrayCopy()
    {
        return [
            'id'     => $this->id,
            'products_qde' => $this->products_qde,
            'products_id' => $this->products_id,
            'transport_type'  => $this->transport_type,
            'calculoFrete'  => $this->calculoFrete,
        ];
    }

    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new DomainException(sprintf(
            '%s does not allow injection of an alternate input filter',
            __CLASS__
        ));
    }

    public function getInputFilter()
    {
        if ($this->inputFilter) {
            return $this->inputFilter;
        }

        $inputFilter = new InputFilter();

        $inputFilter->add([
            'name'     => 'id',
            'required' => true,
            'filters'  => [
                ['name' => 'int'],
            ],
        ]);

        $inputFilter->add([
            'name'       => 'products_qde',
            'required'   => true,
            'filters'    => [
                ['name' => 'StripTags'],
                ['name' => 'StringTrim'],
            ],
            'validators' => [
                [
                    'name'    => 'StringLength',
                    'options' => [
                        'encoding' => 'UTF-8',
                        'min'      => 1,
                        'max'      => 100,
                    ],
                ],
            ],
        ]);

        $inputFilter->add([
            'name'       => 'products_id',
            'required'   => true,
            'filters'    => [
                ['name' => 'StripTags'],
                ['name' => 'StringTrim'],
            ],
            'validators' => [
                [
                    'name'    => 'StringLength',
                    'options' => [
                        'encoding' => 'UTF-8',
                        'min'      => 1,
                        'max'      => 100,
                    ],
                ],
            ],
        ]);

        $inputFilter->add([
            'name'       => 'transport_type',
            'required'   => true,
            'filters'    => [
                ['name' => 'StripTags'],
                ['name' => 'StringTrim'],
            ],
            'validators' => [
                [
                    'name'    => 'StringLength',
                    'options' => [
                        'encoding' => 'UTF-8',
                        'min'      => 1,
                        'max'      => 100,
                    ],
                ],
            ],
        ]);

        $inputFilter->add([
            'name'       => 'transport_id',
            'required'   => true,
            'filters'    => [
                ['name' => 'StripTags'],
                ['name' => 'StringTrim'],
            ],
            'validators' => [
                [
                    'name'    => 'StringLength',
                    'options' => [
                        'encoding' => 'UTF-8',
                        'min'      => 1,
                    ],
                ],
            ],
        ]);
        $this->inputFilter = $inputFilter;

        return $this->inputFilter;
    }
}
