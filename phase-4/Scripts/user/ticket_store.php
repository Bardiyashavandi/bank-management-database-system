<?php

const TICKETS_DB = 'tickets';
const TICKETS_COLLECTION = 'entries';
const TICKETS_NAMESPACE = 'tickets.entries';

function ticket_manager()
{
    static $manager = null;

    if ($manager === null) {
        $manager = new MongoDB\Driver\Manager('mongodb://localhost:27017');
    }

    return $manager;
}

function ticket_object_id($id)
{
    try {
        return new MongoDB\BSON\ObjectId((string) $id);
    } catch (Throwable $error) {
        return null;
    }
}

function ticket_field($document, $field, $default = '')
{
    if (is_object($document)) {
        return $document->{$field} ?? $default;
    }

    if (is_array($document)) {
        return $document[$field] ?? $default;
    }

    return $default;
}

function ticket_is_active($ticket)
{
    return (bool) ticket_field($ticket, 'status', false);
}

function ticket_comments($ticket)
{
    $comments = ticket_field($ticket, 'comments', []);

    if (is_object($comments)) {
        $comments = (array) $comments;
    }

    if (!is_array($comments)) {
        return [];
    }

    return array_values($comments);
}

function ticket_id_string($ticket)
{
    $id = ticket_field($ticket, '_id', '');
    return (string) $id;
}

function ticket_preview($body, $limit = 180)
{
    $body = trim((string) preg_replace('/\s+/', ' ', (string) $body));

    if ($body === '') {
        return '';
    }

    if (function_exists('mb_strlen') && function_exists('mb_substr')) {
        if (mb_strlen($body) <= $limit) {
            return $body;
        }

        return rtrim(mb_substr($body, 0, $limit - 3)) . '...';
    }

    if (strlen($body) <= $limit) {
        return $body;
    }

    return rtrim(substr($body, 0, $limit - 3)) . '...';
}

function ticket_active_usernames(&$error = null)
{
    $error = null;

    try {
        $command = new MongoDB\Driver\Command([
            'distinct' => TICKETS_COLLECTION,
            'key' => 'username',
            'query' => ['status' => true],
        ]);

        $result = ticket_manager()->executeCommand(TICKETS_DB, $command)->toArray();
        $usernames = isset($result[0]->values) ? (array) $result[0]->values : [];
        natcasesort($usernames);

        return array_values($usernames);
    } catch (Throwable $exception) {
        $error = 'Could not load active ticket usernames: ' . $exception->getMessage();
        return [];
    }
}

function ticket_fetch_active_tickets($username = null, &$error = null)
{
    $error = null;

    try {
        $filter = ['status' => true];

        if ($username !== null && $username !== '') {
            $filter['username'] = $username;
        }

        $query = new MongoDB\Driver\Query($filter, ['sort' => ['created_at' => -1]]);
        return ticket_manager()->executeQuery(TICKETS_NAMESPACE, $query)->toArray();
    } catch (Throwable $exception) {
        $error = 'Could not load tickets: ' . $exception->getMessage();
        return [];
    }
}

function ticket_find($id, &$error = null)
{
    $error = null;
    $objectId = ticket_object_id($id);

    if ($objectId === null) {
        $error = 'Invalid ticket id.';
        return null;
    }

    try {
        $query = new MongoDB\Driver\Query(['_id' => $objectId]);
        $results = ticket_manager()->executeQuery(TICKETS_NAMESPACE, $query)->toArray();

        if (!isset($results[0])) {
            $error = 'No ticket found with that id.';
            return null;
        }

        return $results[0];
    } catch (Throwable $exception) {
        $error = 'Could not load ticket: ' . $exception->getMessage();
        return null;
    }
}

function ticket_create($username, $body, &$error = null)
{
    $error = null;

    try {
        $bulk = new MongoDB\Driver\BulkWrite();
        $bulk->insert([
            'username' => trim((string) $username),
            'body' => trim((string) $body),
            'status' => true,
            'created_at' => date('Y-m-d H:i:s'),
            'comments' => [],
        ]);

        ticket_manager()->executeBulkWrite(TICKETS_NAMESPACE, $bulk);
        return true;
    } catch (Throwable $exception) {
        $error = 'Could not create ticket: ' . $exception->getMessage();
        return false;
    }
}

function ticket_add_comment($id, $username, $body, &$error = null)
{
    $error = null;
    $objectId = ticket_object_id($id);

    if ($objectId === null) {
        $error = 'Invalid ticket id.';
        return false;
    }

    try {
        $bulk = new MongoDB\Driver\BulkWrite();
        $bulk->update(
            ['_id' => $objectId],
            ['$push' => [
                'comments' => [
                    'username' => trim((string) $username),
                    'comment' => trim((string) $body),
                    'created_at' => date('Y-m-d H:i:s'),
                ],
            ]],
            ['multi' => false, 'upsert' => false]
        );

        $result = ticket_manager()->executeBulkWrite(TICKETS_NAMESPACE, $bulk);

        if ($result->getMatchedCount() === 0) {
            $error = 'No ticket found with that id.';
            return false;
        }

        return true;
    } catch (Throwable $exception) {
        $error = 'Could not add comment: ' . $exception->getMessage();
        return false;
    }
}

function ticket_resolve($id, &$error = null)
{
    $error = null;
    $objectId = ticket_object_id($id);

    if ($objectId === null) {
        $error = 'Invalid ticket id.';
        return false;
    }

    try {
        $bulk = new MongoDB\Driver\BulkWrite();
        $bulk->update(
            ['_id' => $objectId],
            ['$set' => ['status' => false]],
            ['multi' => false, 'upsert' => false]
        );

        $result = ticket_manager()->executeBulkWrite(TICKETS_NAMESPACE, $bulk);

        if ($result->getMatchedCount() === 0) {
            $error = 'No ticket found with that id.';
            return false;
        }

        return true;
    } catch (Throwable $exception) {
        $error = 'Could not resolve ticket: ' . $exception->getMessage();
        return false;
    }
}
