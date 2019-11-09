<?php

/**
 * This file is part of UBoat - HTTP Botnet Project
 */

class commands extends Model
{
    public function actionPendingCommands()
    {
        return 'SELECT GROUP_CONCAT(b.botId ORDER BY b.botId) As bots, ct.type, c.commandString FROM botcommands b INNER JOIN commands c on b.commandId = c.id INNER JOIN commandtypes ct ON c.commandtype = ct.id WHERE b.result IS NULL GROUP BY ct.type, c.commandString ORDER BY b.commandId DESC';
    }

    public function actionTerminatedCommands()
    {
        return 'SELECT GROUP_CONCAT(b.botId ORDER BY b.botId) As bots, ct.type, b.result, c.commandString FROM botcommands b INNER JOIN commands c on b.commandId = c.id INNER JOIN commandtypes ct ON c.commandtype = ct.id WHERE b.result IS NOT NULL GROUP BY ct.type, c.commandString ORDER BY b.commandId DESC';
    }
}
