<?php

namespace RayNl\LaravelSmtp2go;

class LaravelSmtp2goEmail extends LaravelSmtp2GoApi
{
    protected $searchFilters = [
        'sender',
        'sent_ts',
        'recipient',
        'subject',
        'delivered_ts',
        'status',
        'email_id',
        'smtpcode',
        'host',
        'headers',
    ];

    protected $filters = [
        'start_date',
        'end_date',
        'email_id',
        'username',
        'headers',
        'status_counts',
        'opened_only',
        'clicked_only',
        'ignore_case',
    ];

    public function get()
    {
        return $this->post('/email/search');
    }
}
