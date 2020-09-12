<?php

declare(strict_types=1);

namespace Bclfp\Skeleton\Command;

use Hyperf\Command\Command as HyperfCommand;
use Hyperf\Command\Annotation\Command;
use Hyperf\DbConnection\Db;
use Psr\Container\ContainerInterface;

/**
 * @Command
 */
class sysInstall extends HyperfCommand
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;

        parent::__construct('sys:install');
    }

    public function configure()
    {
        parent::configure();
        $this->setDescription('系统初始化， 安装基本的数据表...');
    }

    public function handle()
    {
        try {
            // install.lock 文件是否存在
            $existInstallFile = false;
            if (file_exists(dirname(__DIR__, 1) . '/install.lock')) {
                $existInstallFile = true;
            };
            if ($existInstallFile) {
                $reInstall = $this->confirm("已存在安装文件 install.lock, 是否要重复安装? ", false);
                if (!$reInstall) {
                    $this->info("未执行数据库初始化数据...");
                    return;
                }
            }

            $confirm = $this->confirm("执行此命令会删除数据库中相同的表结构， 确定要执行么？", false);
            if (!$confirm) {
                $this->warn("未执行数据库初始化数据...");
                return;
            }
            $pdo = Db::connection('default')->getPdo();
            // 获取datavase目录下面的所有sql文件
            $database_dir = dirname(__DIR__, 1) . '/databases';
            foreach (glob($database_dir.'/*.sql') as $sqlFile) {
                $sql = file_get_contents($sqlFile);
                if ('' != trim($sql)) {
                    $pdo->exec($sql);
                    $this->line($sqlFile . '：执行完毕', 'info');
                }
            }
            $string = <<<EOF
    数据初始化完毕。
    管理员初始账户: anmin@admin.com
    管理员处理密码: admin123
EOF;

            // 生成安装文件 'install.lock'
            $fileHandle = fopen(dirname(__DIR__, 1) . '/install.lock', 'aw');
            fwrite($fileHandle, (string)time());
            fclose($fileHandle);
            $this->line($string , 'info');
        } catch (\Throwable $exception) {
            $this->line($exception->getMessage(), 'error');
        }
    }
}
