<?php 

namespace App\Helpers;

class BBCodeDefinitionSet extends \JBBCode\DefaultCodeDefinitionSet
{
	public function __construct()
    {
    	parent::__construct();

        /* [l=home]Home[/l] inner link tag */
        $builder = new \JBBCode\CodeDefinitionBuilder('l', '<a href="' . url('') . '/{option}">{param}</a>');
        $builder->setUseOption(true)->setParseContent(true);
        array_push($this->definitions, $builder->build());

        /* [il]image_name.jpg[/il] inner uploaded image link tag */
        $builder = new \JBBCode\CodeDefinitionBuilder('il', '<img src="' . asset('img/uploaded') . '/{param}">');
        //$builder->setUseOption(true)->setParseContent(true);
        array_push($this->definitions, $builder->build());

        /* [il=x%]image_name.jpg[/il] inner uploaded image link tag with %width */
        $builder = new \JBBCode\CodeDefinitionBuilder('il', '<img style="width: {option}%;" src="' . asset('img/uploaded') . '/{param}">');
        $builder->setUseOption(true)->setParseContent(true);
        array_push($this->definitions, $builder->build());
		
		/* [st=style]text[/st] text will be enclosed with a <div class="style"> */
        $builder = new \JBBCode\CodeDefinitionBuilder('st', '<div class="{option}">{param}</div>');
        $builder->setUseOption(true)->setParseContent(true);
        array_push($this->definitions, $builder->build());
    }
}