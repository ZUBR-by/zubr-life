<?php

namespace App;

class Users
{
    private GraphQLClient $graphQLClient;

    public function __construct(GraphQLClient $graphQLClient)
    {
        $this->graphQLClient = $graphQLClient;
    }

    public function add(int $id) : void
    {
        $query = /** @lang GraphQL */
            <<<'GraphQL'
mutation ($uid: Int!)  {
    insert_telegram_user_one(
        object: {user_id: $uid}, 
        on_conflict: {constraint: telegram_user_id_pk, update_columns: []}
    ) {
        user_id
    }
}
GraphQL;
        $this->graphQLClient->requestAuth($query, ['uid' => $id]);
    }

    public function getById(int $id) : User
    {
        $query = /** @lang GraphQL */
            <<<'GraphQL'
query($uid: Int!)  {
    user: telegram_user_by_pk(user_id: $uid) {
        user_id
        blocked
        token
    }
}
GraphQL;
        $data  = $this->graphQLClient->requestAuth($query, ['uid' => $id]);

        return $data['user']
            ? new User($data['user']['user_id'], ! empty($data['user']['blocked']))
            : User::empty();
    }

}
