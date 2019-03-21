<?php

/**
 * This file is part of the Propel package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license    MIT License
 */

namespace Propel\Generator\Command;

use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Propel\Generator\Manager\ModelManager;

/**
 * @author Florian Klein <florian.klein@free.fr>
 * @author William Durand <william.durand1@gmail.com>
 */
class ModelBuildCommand extends AbstractCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        parent::configure();

        $this
            ->addOption('mysql-engine', null, InputOption::VALUE_REQUIRED,  'MySQL engine (MyISAM, InnoDB, ...)')
            ->addOption('schema-dir', null, InputOption::VALUE_REQUIRED,  'The directory where the schema files are placed')
            ->addOption('output-dir', null, InputOption::VALUE_REQUIRED, 'The output directory')
            ->addOption('object-class', null, InputOption::VALUE_REQUIRED, 'The object class generator name')
            ->addOption('object-stub-class', null, InputOption::VALUE_REQUIRED, 'The object stub class generator name')
            ->addOption('object-multiextend-class', null, InputOption::VALUE_REQUIRED, 'The object multiextend class generator name')
            ->addOption('query-class', null, InputOption::VALUE_REQUIRED, 'The query class generator name')
            ->addOption('query-stub-class', null, InputOption::VALUE_REQUIRED, 'The query stub class generator name')
            ->addOption('query-inheritance-class', null, InputOption::VALUE_REQUIRED, 'The query inheritance class generator name')
            ->addOption('query-inheritance-stub-class', null, InputOption::VALUE_REQUIRED, 'The query inheritance stub class generator name')
            ->addOption('tablemap-class', null, InputOption::VALUE_REQUIRED, 'The tablemap class generator name')
            ->addOption('pluralizer-class', null, InputOption::VALUE_REQUIRED, 'The pluralizer class name')
            ->addOption('enable-identifier-quoting', null, InputOption::VALUE_NONE, 'Identifier quoting may result in undesired behavior (especially in Postgres)')
            ->addOption('target-package', null, InputOption::VALUE_REQUIRED, '', '')
            ->addOption('disable-package-object-model', null, InputOption::VALUE_NONE, 'Disable schema database merging (packageObjectModel)')
            ->addOption('disable-namespace-auto-package', null, InputOption::VALUE_NONE, 'Disable namespace auto-packaging')
            ->addOption('composer-dir', null, InputOption::VALUE_REQUIRED, 'Directory in which your composer.json resides', null)
            ->setName('model:build')
            ->setAliases(['build'])
            ->setDescription('Build the model classes based on Propel XML schemas')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $configOptions = [];
        $inputOptions = $input->getOptions();

        foreach ($inputOptions as $key => $option) {
            if (null !== $option) {
                switch ($key) {
                    case 'schema-dir':
                        $configOptions['propel']['paths']['schemaDir'] = $option;
                        break;
                    case 'output-dir':
                        $configOptions['propel']['paths']['phpDir'] = $option;
                        break;
                    case 'object-class':
                        $configOptions['propel']['generator']['objectModel']['builders']['object'] = $option;
                        break;
                    case 'object-stub-class':
                        $configOptions['propel']['generator']['objectModel']['builders']['objectstub'] = $option;
                        break;
                    case 'object-multiextend-class':
                        $configOptions['propel']['generator']['objectModel']['builders']['objectmultiextend'] = $option;
                        break;
                    case 'query-class':
                        $configOptions['propel']['generator']['objectModel']['builders']['query'] = $option;
                        break;
                    case 'query-stub-class':
                        $configOptions['propel']['generator']['objectModel']['builders']['querystub'] = $option;
                        break;
                    case 'query-inheritance-class':
                        $configOptions['propel']['generator']['objectModel']['builders']['queryinheritance'] = $option;
                        break;
                    case 'query-inheritance-stub-class':
                        $configOptions['propel']['generator']['objectModel']['builders']['queryinheritancestub'] = $option;
                        break;
                    case 'tablemap-class':
                        $configOptions['propel']['generator']['objectModel']['builders']['tablemap'] = $option;
                        break;
                    case 'pluralizer-class':
                        $configOptions['propel']['generator']['objectModel']['pluralizerClass'] = $option;
                        break;
                    case 'composer-dir':
                        $configOptions['propel']['paths']['composerDir'] = $option;
                        break;
                    case 'disable-package-object-model':
                        if ($option) {
                            $configOptions['propel']['generator']['packageObjectModel'] = false;
                        }
                        break;
                    case 'disable-namespace-auto-package':
                        if ($option) {
                            $configOptions['propel']['generator']['namespaceAutoPackage'] = false;
                        }
                        break;
                    case 'mysql-engine':
                        $configOptions['propel']['database']['adapters']['mysql']['tableType'] = $option;
                        break;
                }
            }
        }

        $generatorConfig = $this->getGeneratorConfig($configOptions, $input);
        $this->createDirectory($generatorConfig->getSection('paths')['phpDir']);

        $manager = new ModelManager();
        $manager->setFilesystem($this->getFilesystem());
        $manager->setGeneratorConfig($generatorConfig);
        $manager->setSchemas($this->getSchemas($generatorConfig->getSection('paths')['schemaDir'], $generatorConfig->getSection('generator')['recursive']));
       
            
         $output->writeln('SCHEMA IN manager of MODEL BUILDER:'.var_dump(array_keys($manager->getSchemas())));
        //TODO for every table in the schema add the behaviour inhrithing from CI_Model
        // adding CI_Model table
        $xml = simplexml_load_file(array_keys($manager->getSchemas())[0]);
        // Create a child in the first topic node
        
        //for every table in the schema add the behaviour inhrithing from CI_Model
        $beaviour = new \SimpleXMLElement('<behavior name="concrete_inheritance">
            <parameter/><parameter />  </behavior>');
        foreach($xml->table as $table){
            $current_behav = $table->addChild($beaviour->getName(), (string) $beaviour);
            foreach($beaviour->attributes() as $n => $v) { 
                $current_behav->addAttribute($n, $v); 
            }
            $i = 0; 
            foreach($beaviour->children() as $children){
                $param = $current_behav->addChild($children->getName(),(string)$children);
                $output->writeln(var_dump($indx));
                if($i%2==0){
                    $param->addAttribute('name','extends');
                    $param->addAttribute('value','CI_Model');  
                }else{      
                    $param->addAttribute('name','copy_data_to_parent');
                    $param->addAttribute('value','false');
                }
                $i++;
            }
        }
  
        $database = $xml[0];
        $child = $database->addChild("table");
        // Add the text attribute
        $child->addAttribute("name", "CI_Model");
        //idMethod="native" phpName="Category
        $child->addAttribute("idMethod", "native");
        $child->addAttribute("phpName", "CI_Model");
        
        $xml->asXML(array_keys($manager->getSchemas())[0]);
        
    //-------------------------
        //TODO:test this
        preg_match('/(.+\/models\/)(\w+\/)\w+/', array_keys($manager->getSchemas())[0],$models_path);//array_keys($manager->getSchemas())
       
        $autoload_location = __DIR__.'/../../../../vendor/autoload.php';
        var_dump($models_path);
        $template = "<?php

class Ci_propel_autoloader {

    public function __construct() {
        \$this->init_autoloader();
    }

    private function init_autoloader(){
        spl_autoload_register(function(\$classname){
            require('{$autoload_location}');
            ";
            
            foreach($xml->table as $table){
                $template.=
                "
                require_once(APPPATH.'models/".$models_path[2]."generated-reversed-database/generated-classes/Base/".ucfirst($table['name']).".php');
                //require_once(APPPATH.'models/".$models_path[2]."generated-reversed-database/generated-classes/Base/".ucfirst($table['name'])."Query.php');";
            }
            $template.=
            "     
            }); 
                }
            }";
        file_put_contents($models_path[1].'ci_propel_autoloader.php', $template, LOCK_EX);
        
        
        //---------------------
        
        $manager->setLoggerClosure(function ($message) use ($input, $output) {
            if ($input->getOption('verbose')) {
                $output->writeln($message);
            }
        });
        $manager->setWorkingDirectory($generatorConfig->getSection('paths')['phpDir']);

        $manager->build();
    }
}
