<?php

namespace App\Enum;

class RelationEnum
{
    public const ONE_TO_ONE   = 'Doctrine\\ORM\\Mapping\\OneToOne';
    public const ONE_TO_MANY  = 'Doctrine\\ORM\\Mapping\\OneToMany';
    public const MANY_TO_ONE  = 'Doctrine\\ORM\\Mapping\\ManyToOne';
    public const MANY_TO_MANY = 'Doctrine\\ORM\\Mapping\\ManyToMany';
}
