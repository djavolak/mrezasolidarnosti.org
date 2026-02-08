<?php
namespace Solidarity\Transaction\Factory;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Skeletor\Core\Factory\AbstractFactory;
use Solidarity\Donor\Entity\Donor;
use Solidarity\Educator\Entity\Educator;
use Solidarity\Transaction\Entity\Transaction;

class TransactionFactory extends AbstractFactory
{
    public static function compileEntityForCreate($data, $em): ?int
    {
        $transaction = new Transaction();
        $transaction->amount = $data['amount'];
        $transaction->status = $data['status'];
        $transaction->donor = $em->getRepository(Donor::class)->find($data['donor']);
        $transaction->educator = $em->getRepository(Educator::class)->find($data['educator']);
        $transaction->accountNumber = $transaction->educator->accountNumber;
        $em->persist($transaction);
        $em->flush();

        return $transaction->id;
    }

    public static function compileEntityForUpdate($data, $em)
    {
        $transaction = $em->getRepository(Transaction::class)->find($data['id']);
        $transaction->status = $data['status'];
        //@TODO might be required to change when error occurs, but can still cancel and create new
        $transaction->accountNumber = $transaction->educator->accountNumber;
        //@TODO when donor pays different amount
        $transaction->amount = $data['amount'];
        $transaction->comment = $data['comment'];
        //@TODO does not need to change, can cancel existing and create new?
//        $transaction->donor = $em->getRepository(Donor::class)->find($data['donor']);
//        $transaction->educator = $em->getRepository(Educator::class)->find($data['educator']);

        return $transaction->id;
    }
}