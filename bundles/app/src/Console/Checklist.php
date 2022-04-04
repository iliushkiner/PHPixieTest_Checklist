<?php

namespace Project\App\Console;

use PHPixie\Console\Command\Config;
use PHPixie\Slice\Data;

/**
 * Command to display latest messages in the console
 */
class Checklist extends Command
{
    /**
     * Command configuration
     * @param Config $config
     */
    protected function configure($config)
    {
        // Set command description
        $config->description("Adding checklist item");

        // add an option for Name of the checklist item
        $config->option('name')
            ->description("Name of the checklist item");

        // add an argument to configure the checklist item description
        $config->option('desc')
            ->description("Item description text maximum length 255 chars");
        
        // add an argument to configure the checklist item parent
        $config->argument('parentId')
            ->description("Checklist item parent id");
    }

    /**
     * @param Data $argumentData
     * @param Data $optionData
     */
    public function run($argumentData, $optionData)
    {
        $name = $optionData->get('name', '');
        $desc= $optionData->get('desc', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.');
        $parentId = $argumentData->get('parentId', 0);
        
        if(empty($name) || $name ==''){
            $this->writeLine("No input item name");
        } else {
            $isLvl_1 = TRUE;
            if ($parentId>0){
                $cheklistQuery = $this->components()->orm()->query('checklist')->where('id', $parentId)->startWhereConditionGroup('and')->where('parentid', NULL)->or('parentid',0);
                $isLvl_1 = ($cheklistQuery->count()>0);
            }
            if ($isLvl_1){
                $checklistitem = $this->components()->orm()->createEntity('checklist', array(
                    'name'   => $name,
                    'descr'   => $desc,
                    'parentid' => $parentId>0 ? $parentId : NULL 
                ));
                $checklistitem->save();
                $this->writeLine("Checklist Item added");
            } else {
                $this->writeLine("Checklist Item not added, because parent item not found or is not a first level item");
            }
        }
        
    }
}