<?php

namespace Eltharin\ReloadableFieldBundle\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Filesystem;


#[AsCommand(
	name: 'eltharinreloadablefield:copyroutefile',
	description: 'Copy the route file',
)]
class CopyRouteFileCommand extends Command
{
	private string $projectDir;

	public function __construct(string $projectDir, string $name = null)
	{
		parent::__construct($name);
		$this->projectDir = $projectDir;
	}

	protected function configure() : void
	{
		$this
			->setHelp('Copy route file')
		;
	}

	protected function execute(InputInterface $input, OutputInterface $output): int
	{
		$io = new SymfonyStyle($input, $output);
		$fs = new Filesystem();

		$exist = $fs->exists($this->projectDir . \DIRECTORY_SEPARATOR . 'config' . \DIRECTORY_SEPARATOR . 'routes' . \DIRECTORY_SEPARATOR . 'eltharin_reloadablefieldsbundele_routes.yaml');

		if($exist)
		{
			$overide = $io->choice(
				'File already exists, do you want override it?',['y' => 'Yes','n' => 'No']
			);

			if($overide == 'n')
			{
				$io->caution('File already Exist, Command aborded');
				return Command::FAILURE;
			}
		}

		$fs->copy(
			__DIR__ . \DIRECTORY_SEPARATOR . '..' . \DIRECTORY_SEPARATOR . '..' . \DIRECTORY_SEPARATOR . 'config' . \DIRECTORY_SEPARATOR . 'routes' . \DIRECTORY_SEPARATOR . 'eltharin_reloadablefieldsbundele_routes.yaml'
			,$this->projectDir . \DIRECTORY_SEPARATOR . 'config' . \DIRECTORY_SEPARATOR . 'routes' . \DIRECTORY_SEPARATOR . 'eltharin_reloadablefieldsbundele_routes.yaml'
		);

		$io->success('File copied!');

		return Command::SUCCESS;
	}
}