<?php
namespace App\Command;

use App\Service\FormatCSV;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TableCSV extends Command
{
    protected static $defaultName = 'app:tableCSV';

    protected function configure()
    {
        $this->addArgument('fileDirectory', InputArgument::REQUIRED, 'The file directory');
        $this->addArgument('Json', InputArgument::OPTIONAL, 'Return Json');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $formatCSV = new FormatCSV();

        $array = $formatCSV->CSVToArray($input->getArgument('fileDirectory'));

        
        $arrayFormate = $formatCSV->FormateDate($array);
        $arrayFormate = $formatCSV->FormatePrice($arrayFormate);
        $arrayFormate = $formatCSV->formateEnable($arrayFormate);
        $arrayFormate = $formatCSV->formateTitle($arrayFormate);

        if($input->getArgument('Json') == 'Json' && !empty($input->getArgument('Json')))
        {
            $output->writeln($arrayFormate = json_encode($arrayFormate));
        }
        else
        {
            $table = new Table($output);
            $table->setHeaders(array_keys($arrayFormate[0]));
            $table->setRows(array_values($arrayFormate));
            $table->render();
        }

        return Command::SUCCESS;
    }
}