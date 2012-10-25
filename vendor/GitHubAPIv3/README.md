README
======

This is a PHP API For GitHub v3 API.

There are zero dependencies.  This library uses the ssl:// and
http:// steam contexts to interact with the github.com API.  That means
that the only requirement is that `allow_url_fopen = On` in your PHP
ini settings (which is on by default).

The API of this library mimics the documentation presented at
http://developer.github.com.  Information returned from the GitHub API
is then processed and converted into Entity objects.  These object's
data can be retrieved by the getter methods on each object (described
below).  In cases where GitHub allows for new information to be posted
back, the same Entity objects can be used, and the setter methods on
these objects will expose which information GitHub accepts back.


### Authentication

All API constructors take authentication in one of the following
formats:

#### Basic Authentication

```php
use GitHubAPIv3\PullRequest\PullRequestAPI;
$api = new PullRequestAPI('uname', 'password');
// or
$api = new PullRequestAPI(array('username' => 'uname', 'password' => 'pword'));
```
    
#### OAuth Token Authentication

```php
use GitHubAPIv3\PullRequest\PullRequestAPI;
// must be 40 char token from github
$api = new PullRequestAPI('1234567890abcdefghij1234567890abcdefghij'); 
```
    
### Pull Reqests

#### APIs

API object

    use GitHubAPIv3\PullRequest\PullRequestAPI;
    $api = new PullRequestAPI($token);

List Pull Requests - http://developer.github.com/v3/pulls/#list-pull-requests

    $prs = $api->getPullRequests('zendframework', 'zf2', array('state' => 'closed', 'per_page' => 100));
    
#### Entities
    
The PullRequest Entity:

    class PullRequest extends AbstractEntity
    {
        public function getUrl();
        public function getHtmlUrl();
        public function getDiffUrl();
        public function getPatchUrl();
        public function getNumber();
        public function getState();
        public function getTitle();
        public function getBody()
        public function getCreatedAt()
        public function getUpdatedAt()
        public function getClosedAt()
        public function getMergedAt()
        
        /**
         * @return \GitHubAPIv3\User\BasicUser
         */
        public function getUser();

        /**
         * @return \GitHubAPIv3\User\BasicUser
         */
        public function getAssignee();

        /**
         * @return \GitHubAPIv3\Issue\Milestone
         */
        public function getMilestone();
    }

