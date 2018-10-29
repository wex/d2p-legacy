<?php
declare(strict_types=1);

namespace Wex\ActiveRecord;

use \Wex\App;

/**
 * Harry the Wizard
 */

trait Harry {

    public static function createSql()
    {
        $instance = new static;
        $blueprint = $instance->bluePrint();

        $sql = '';
        $sql .= sprintf("CREATE TABLE `%s` (\n", $blueprint->table);
        $sql .= sprintf("\t`%s` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,\n", $blueprint->pk);

        foreach ($blueprint->columns as $column) {
			$sql .= (function($column) {
				switch (str_replace('Wex\ActiveRecord\Blueprint\Column\\', '', get_class($column))) {
					case 'Boolean':
						return sprintf("\t`%s` TINYINT(1) NOT NULL DEFAEULT %d,\n",
							$column->name,
							$column->default ? 1 : 0
						);
					case 'Varchar':
						if ($column->enum) {
							return sprintf("\t`%s` ENUM(%s) %s %s,\n",
								$column->name,
								App::$db->getPlatform()->quoteValueList($column->enum),
								$column->required ? 'NOT NULL' : 'NULL',
								!is_null($column->default) ? sprintf('DEFAULT %s', App::$db->getPlatform()->quoteValue($column->default)) : ($column->required ? '' : 'DEFAULT NULL')
							);
						}
						return sprintf("\t`%s` VARCHAR(%d) %s NULL %s,\n",
							$column->name,
							$column->max ?? 255,
							$column->required ? 'NOT' : '',
							!is_null($column->default) ? sprintf('DEFAULT %s', App::$db->getPlatform()->quoteValue($column->default)) : ($column->required ? '' : 'DEFAULT NULL')
						);
					case 'Integer':
						return sprintf("\t`%s` INT(%d) %s %s NULL %s,\n",
							$column->name,
							($column->min < 0) ? 11 : 10,
							($column->min < 0) ? '' : 'UNSIGNED ',
							$column->required ? 'NOT' : '',
							!is_null($column->default) ? sprintf('DEFAULT %s', App::$db->getPlatform()->quoteValue($column->default)) : ($column->required ? '' : 'DEFAULT NULL')
						);
					case 'Timestamp':
						return sprintf("\t`%s` TIMESTAMP %s NULL %s,\n",
							$column->name,
							$column->required ? 'NOT' : '',
							!is_null($column->default) ? sprintf('DEFAULT %s', App::$db->getPlatform()->quoteValue($column->default)) : ($column->required ? '' : 'DEFAULT NULL')
						);
					case 'Decimal':
						return sprintf("\t`%s` DECIMAL(%d,%d) %s NULL %s,\n",
							$column->name,
							12,
							4,
							$column->required ? 'NOT' : '',
							!is_null($column->default) ? sprintf('DEFAULT %s', App::$db->getPlatform()->quoteValue($column->default)) : ($column->required ? '' : 'DEFAULT NULL')
						);
				}
			})($column);			
		}

		$sql .= sprintf("\tPRIMARY KEY (`%s`)", $blueprint->pk);

        foreach ($blueprint->columns as $column) {
			$sql .= (function($column) {
				switch ($column->index) {
					case 'index':
						return sprintf(",\n\tINDEX `%s` (`%s`)",
							$column->name,
							$column->name
						);
					case 'unique':
						return sprintf(",\n\tUNIQUE INDEX `%s` (`%s`)",
							$column->name,
							$column->name
						);
				}
			})($column);			
		}

		$sql .= "\n)\n";
		$sql .= "COLLATE='utf8_bin'\n";
		$sql .= "ENGINE=InnoDB;\n";

        return $sql;
/*
	`added` DATETIME NOT NULL,
	`changed` DATETIME NOT NULL,
	PRIMARY KEY (`id`),
	UNIQUE INDEX `uri` (`uri`, `rev`),
	INDEX `rank` (`rank`),
	INDEX `rev` (`rev`),
	INDEX `published` (`published`),
	INDEX `state` (`state`),
	INDEX `type` (`type`),
	INDEX `access` (`access`),
	INDEX `lang` (`lang`),
	INDEX `visibility` (`visibility`),
	INDEX `added` (`added`),
	INDEX `changed` (`changed`)
)
COLLATE='utf8_bin'
ENGINE=InnoDB
AUTO_INCREMENT=2
;

*/


        print_r( $blueprint );
    }

}