<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2024-06-28 17:33:40
 */

namespace Diepxuan\EAVCleaner\Console\Command;

use Magento\Framework\App\ResourceConnection;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class EavAttributesRestoreDefault extends Command
{
    /**
     * @var AdapterInterface
     */
    protected $connection;

    /**
     * @var InputInterface
     */
    protected $input;

    /**
     * @var OutputInterface
     */
    protected $output;

    public function __construct(
        ResourceConnection $resourceConnection,
    ) {
        $this->connection = $resourceConnection->getConnection();
        parent::__construct();
    }

    /**
     * Executes the current command.
     *
     * @param InputInterface  $input  An InputInterface instance
     * @param OutputInterface $output An OutputInterface instance
     *
     * @return void;
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $this->input  = $input;
        $this->output = $output;
        array_map(function ($tableName): void {
            // $this->output("[<fg=green>✔</>] Imported <fg=green>{$sCategory->category->sku}</> ({$index}/{$total})");
            $this->output("[i] Clean from table <fg=green>{$tableName}</>");
            $this->tableCleaner($tableName);
        }, ['varchar', 'int', 'decimal', 'text', 'datetime']);

        return 0;
    }

    /**
     * Console output.
     *
     * @param mixed $str
     */
    public function output(string $str): void
    {
        $this->output->writeln($str);
    }

    /**
     * Init command.
     */
    protected function configure(): void
    {
        $this
            ->setName('eav:attributes:restoredefault')
            ->setDescription("Restore product(s) attribute 'Use Default Value'.")
        ;
    }

    private function cleanerAttribute($fullTableName, $attribute): void
    {
        extract($attribute);
        $this->connection->query("DELETE FROM {$fullTableName} WHERE value_id = {$value_id}");
        $this->output(
            <<<EOF
                [<fg=green>✔</>] Deleted value {$value_id}
                    * value     {$value}
                    * attribute {$attribute_id}
                    * table     {$fullTableName}
                EOF
        );
    }

    private function tableCleaner($tableName): void
    {
        $fullTableName = $this->connection->getTableName('catalog_product_entity_' . $tableName);
        $rows          = $this->connection->fetchAll('SELECT * FROM ' . $fullTableName . ' WHERE store_id != 0');

        array_map(function ($attribute) use ($fullTableName): void {
            $this->cleanerAttribute($fullTableName, $attribute);
        }, $rows);
    }
}
