<?php


namespace totum\commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use totum\common\configs\ConfParent;
use totum\common\configs\MultiTrait;
use totum\common\Services\Services;
use totum\config\Conf;

class CleanSchemaTmpTables extends Command
{
    protected function configure()
    {
        $this->setName('clean-schema-tmp-tables')
            ->setDescription('Clean tmp_tables in schemas. For single install. Set in crontab one time in 10 minutes.');
        if (key_exists(MultiTrait::class, class_uses(Conf::class, false))) {
            $this->addArgument('schema', InputOption::VALUE_REQUIRED, 'Enter schema name');
        }
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $Conf = new Conf();

        if (is_callable([$Conf, 'setHostSchema'])) {
            if ($schema = $input->getArgument('schema')) {
            }
        }
        if (empty($schema)) {
            $schema = $Conf->getSchema(true);
        }


        static::doSqlWorks($schema, $Conf);

        return 0;
    }

    static function doSqlWorks(string $schema, ConfParent $Conf)
    {
        $sql = $Conf->getSql(true, false);

        $dayAgo = date_create();
        $dayAgo->modify('-24 hours');

        try {
            $sql->exec('delete from "' . $schema . '"._tmp_tables where touched<\'' . $dayAgo->format('Y-m-d H:i') . '\'');

            $hourAgo = date_create();
            $hourAgo->modify('-1 hour');
            try {
                $sql->exec('delete from "' . $schema . '"._services_vars where expire<\'' . $hourAgo->format('Y-m-d H:i:s') . '\'');
            } catch (\Exception $exception) {
                /*if ($exception->getCode() === '42P01') {

                }*/
            }

            $twoHoursAgo = date_create();
            $twoHoursAgo->modify('-2 hours');

            $sql->exec('delete from "' . $schema . '"._tmp_tables where table_name SIMILAR TO \'\_%\' AND touched<\''
                . $twoHoursAgo->format('Y-m-d H:i') . '\'');

            $sql->exec('VACUUM "' . $schema . '"._tmp_tables');
        } catch (\Exception $e) {
            echo $e->getMessage();
            debug_print_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
        }
    }

}
