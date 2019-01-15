<?php
namespace App\Command;

use App\Entity\Happy;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

class HappyCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('app:want-happy')
            ->setDescription('If you have a bsd mood try it command')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln([
            '',
            'I believe in you, bro',
            '',
            '=====================',
            '',
            'Everything will be ok!',
            '',
            'P.S. or no, but dont worry :)',
        ]);
    }
}
