<?php

namespace App\Command;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class CreateDefaultAdminUserCommand extends Command
{
    protected static $defaultName = 'app:create-default-admin-user';
    protected static $defaultDescription = 'Create the default administrator user';

    private $em;
    private $passwordHasher;

    private $adminAccountEmail;
    private $adminAccountPassword;

    /** @var UserRepository */
    private $userRepository;

    public function __construct(ManagerRegistry $doctrine, UserPasswordHasherInterface $passwordHasher, ParameterBagInterface $params)
    {
        $this->em = $doctrine->getManager();
        $this->passwordHasher = $passwordHasher;

        $this->adminAccountEmail = $params->get('app.admin_account.email');
        $this->adminAccountPassword = $params->get('app.admin_account.password');

        $this->userRepository = $this->em->getRepository(User::class);

        parent::__construct();
    }

    protected function configure(): void
    {

    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        if ($this->userRepository->findOneBy(['email' => $this->adminAccountEmail])) {
            $io->note('A user with email "'. $this->adminAccountEmail .'" already exists.');

            return Command::SUCCESS;
        }

        $user = new User();
        $user->setEmail($this->adminAccountEmail);
        $user->setRoles(['ROLE_ADMIN']);

        if ($this->adminAccountPassword !== null && $this->adminAccountPassword !== '') {
            $user->setPassword($this->passwordHasher->hashPassword($user, $this->adminAccountPassword));
        }

        $this->em->persist($user);
        $this->em->flush();

        $io->success(sprintf('Default admin user "%s" successfully created. Password: %s.', $this->adminAccountEmail, $this->adminAccountPassword === null  || $this->adminAccountPassword === '' ? 'No' : 'Yes'));

        return Command::SUCCESS;
    }
}
