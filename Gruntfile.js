module.exports = function(grunt) {
    grunt.initConfig({
        shell: {
            generateZip: {
                command:
                    'cp afterpay.zip afterpay-$(git rev-parse --abbrev-ref HEAD).zip \n'
            },
            autoindex: {
                command:
                    'composer global require pagantis/autoindex \n' +
                    'php ~/.composer/vendor/pagantis/autoindex/index.php ./ || true \n' +
                    'php /home/circleci/.config/composer/vendor/pagantis/autoindex/index.php . || true \n'

            },
            composerProd: {
                command: 'composer install --no-dev'
            },
            composerDev: {
                command: 'composer install --ignore-platform-reqs'
            },
            runTestPrestashop17: {
                command:
                    'docker-compose down\n' +
                    'docker-compose up -d selenium\n' +
                    'docker-compose up -d prestashop17-test\n' +
                    'echo "Creating the prestashop17-test"\n' +
                    'sleep 100\n' +
                    'date\n' +
                    'docker-compose logs prestashop17-test\n' +
                    'set -e\n' +
                    'vendor/bin/phpunit --group prestashop17basic\n'
            },
            runTestPrestashop16: {
                command:
                    'docker-compose down\n' +
                    'docker-compose up -d selenium\n' +
                    'docker-compose up -d prestashop16-test\n' +
                    'echo "Creating the prestashop16-test"\n' +
                    'sleep  90\n' +
                    'date\n' +
                    'docker-compose logs prestashop16-test\n' +
                    'set -e\n' +
                    'vendor/bin/phpunit --group prestashop16basic\n'
            },
        },
        compress: {
            main: {
                options: {
                    archive: 'afterpay.zip'
                },
                files: [
                    {src: ['controllers/**'], dest: 'afterpay/', filter: 'isFile'},
                    {src: ['classes/**'], dest: 'afterpay/', filter: 'isFile'},
                    {src: ['docs/**'], dest: 'afterpay/', filter: 'isFile'},
                    {src: ['override/**'], dest: 'afterpay/', filter: 'isFile'},
                    {src: ['logs/**'], dest: 'afterpay/', filter: 'isFile'},
                    {src: ['vendor/**'], dest: 'afterpay/', filter: 'isFile'},
                    {src: ['translations/**'], dest: 'afterpay/', filter: 'isFile'},
                    {src: ['upgrade/**'], dest: 'afterpay/', filter: 'isFile'},
                    {src: ['optionaloverride/**'], dest: 'afterpay/', filter: 'isFile'},
                    {src: ['oldoverride/**'], dest: 'afterpay/', filter: 'isFile'},
                    {src: ['sql/**'], dest: 'afterpay/', filter: 'isFile'},
                    {src: ['lib/**'], dest: 'afterpay/', filter: 'isFile'},
                    {src: ['defaultoverride/**'], dest: 'afterpay/', filter: 'isFile'},
                    {src: ['views/**'], dest: 'afterpay/', filter: 'isFile'},
                    {src: '.htaccess', dest: 'afterpay/'},
                    {src: 'index.php', dest: 'afterpay/'},
                    {src: 'afterpay.php', dest: 'afterpay/'},
                    {src: 'logo.png', dest: 'afterpay/'},
                    {src: 'README.md', dest: 'afterpay/'}
                ]
            }
        }
    });

    grunt.loadNpmTasks('grunt-shell');
    grunt.loadNpmTasks('grunt-contrib-compress');
    grunt.registerTask('default', [
        'shell:composerProd',
        'shell:autoindex',
        'compress',
        'shell:generateZip',
        'shell:composerDev'
    ]);
    //manually run the selenium test: "grunt shell:testPrestashop16"
};