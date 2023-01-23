<?php

namespace Baumeister\ZalandoClient\Model;

enum TypeUsage: string
{
    case literal = 'literal';
    case reference_by_label = 'reference_by_label';
    case reference_by_value = 'reference_by_value';
}
