<?php

namespace Baumeister\ZalandoClient\Model;

enum TypeDefinitionValue: string
{
    case BooleanDefinition = 'BooleanDefinition';
    case StringDefinition = 'StringDefinition';
    case LocalizedStringDefinition = 'LocalizedStringDefinition';
    case IntegerDefinition = 'IntegerDefinition';
    case DecimalDefinition = 'DecimalDefinition';
    case DatetimeDefinition = 'DatetimeDefinition';
    case StructuredDefinition = 'StructuredDefinition';
}
