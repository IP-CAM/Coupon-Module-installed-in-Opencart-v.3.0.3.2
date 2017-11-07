<?php

class ModelExtensionModuleWsEventManager extends Model
{
    public function deleteEvent($code) {
        //if you have several events under one code - they will all be deleted.
        //please use deleteEventById.

        if(VERSION >= '3.0.0.0'){
            $this->load->model('setting/event');
            return $this->model_setting_event->deleteEventByCode($code);
        }elseif(VERSION > '2.0.0.0'){
            $this->load->model('extension/event');
            return $this->model_extension_event->deleteEvent($code);
        }else{

            $this->db->query("DELETE FROM " . DB_PREFIX . "event WHERE `code` = '" . $this->db->escape($code) . "'");

        }

    }
}