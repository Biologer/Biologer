# Contribution Guide

- [Bug Reports](#bug-reports)
- [Development](#development)
- [Security Vulnerabilities](#security-vulnerabilities)
- [Coding Style](#coding-style)
    - [PHPDoc](#phpdoc)

<a name="bug-reports"></a>
## Bug Reports

To encourage active collaboration, we strongly encourage pull requests, not just bug reports. "Bug reports" may also be sent in the form of a pull request containing a failing test.

However, if you file a bug report, your issue should contain a title and a clear description of the issue. You should also include as much relevant information as possible and a code sample that demonstrates the issue. The goal of a bug report is to make it easy for yourself - and others - to replicate the bug and develop a fix.

Remember, bug reports are created in the hope that others with the same problem will be able to collaborate with you on solving it. Do not expect that the bug report will automatically see any activity or that others will jump to fix it. Creating a bug report serves to help yourself and others start on the path of fixing the problem.

<a name="development"></a>
## Development

You may propose new features or improvements of existing behavior by creating an issue. If you propose a new feature, please be willing to implement at least some of the code that would be needed to complete the feature. Please note that the feature must be useful to other users of Biologer, so before you get started create an issue to discuss it.

When implementing a feature or improvement, you should fork the project, work on a dedicated branch on your fork, and submit a pull request once you've finished. The code will be reviewed and if it meets required criteria it will be merged.


<a name="security-vulnerabilities"></a>
## Security Vulnerabilities

If you discover a security vulnerability, please send an email to our admins at [admin@biologer.org](mailto:admin@biologer.org). All security vulnerabilities will be promptly addressed.

<a name="coding-style"></a>
## Coding Style

Biologer follows the [PSR-2](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md) coding standard and the [PSR-4](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-4-autoloader.md) autoloading standard.

To easily check you code style, you can use [php-cs-fixer](https://github.com/FriendsOfPHP/PHP-CS-Fixer); this project contains configuration file to run it.

<a name="phpdoc"></a>
### PHPDoc

Below is an example of a valid documentation block. Note that the `@param` attribute is followed by two spaces, the argument type, two more spaces, and finally the variable name:

    /**
     * Register a binding with the container.
     *
     * @param  string|array  $abstract
     * @param  \Closure|string|null  $concrete
     * @param  bool  $shared
     * @return void
     */
    public function bind($abstract, $concrete = null, $shared = false)
    {
        //
    }
