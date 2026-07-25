<?php


namespace totum\commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use totum\common\errorException;
use totum\common\TotumInstall;
use totum\common\User;
use totum\config\Conf;

class SchemaAdd extends Command
{
    protected function configure()
    {
        $this->setName('schema-add')
            ->setDescription('Add new schema')
            ->addArgument('name', InputArgument::REQUIRED, 'Enter schema name')
            ->addArgument('host', InputArgument::REQUIRED, 'Enter schema host')
            ->addArgument('user_login', InputOption::VALUE_REQUIRED, 'Enter totum admin login', 'admin')
            ->addArgument('user_pass', InputOption::VALUE_REQUIRED, 'Enter totum admin password', '1111');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if (!class_exists(Conf::class)) {
            $output->writeln('ERROR: config class not found');
        }
        $Conf=new Conf();

        if (!$input->getArgument('name')) {
            throw new errorException('Enter schema name');
        }
        if (!$input->getArgument('host')) {
            throw new errorException('Enter schema host');
        }

        $Conf->setHostSchema($input->getArgument('host'), $input->getArgument('name'));

        $TotumInstall=new TotumInstall($Conf, new User(['login' => 'service', 'roles' => ["1"], 'id' => 1], $Conf), $output);

        $schemaConfig = [];
        $schemaConfig['schema_exists'] = false;
        $schemaConfig['user_login'] = $input->getArgument('user_login');
        $schemaConfig['user_pass'] = $input->getArgument('user_pass');


        $TotumInstall->createSchema($schemaConfig, function ($file) {
            return dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'moduls' . DIRECTORY_SEPARATOR . 'install' . DIRECTORY_SEPARATOR . $file;
        });



        $output->writeln('save Conf.php');

        $configFile = (new \ReflectionClass(Conf::class))->getFileName();
        $configFileContent = file_get_contents($configFile);

        if (!preg_match('~\/\*\*\*getSchemas\*\*\*\/[^$]*{[^$]*return([^$]*)\}[^$]*/\*\*\*getSchemasEnd\*\*\*/~', $configFileContent, $matches)) {
            throw new \Exception('Format of file not correct. Can\'t replace function getSchemas');
        }
        eval("\$schemas={$matches[1]}");
        $schemas[$input->getArgument('host')]=$input->getArgument('name');
        $configFileContent = preg_replace('~(\/\*\*\*getSchemas\*\*\*\/[^$]*{[^$]*return\s*)([^$]*)(\}[^$]*/\*\*\*getSchemasEnd\*\*\*/)~', '$1'.var_export($schemas, 1).';$3', $configFileContent);
        copy($configFile, $configFile.'_old');
        file_put_contents($configFile, $configFileContent);

        return 0;
    }
}
