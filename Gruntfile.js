module.exports = function(grunt) {
    grunt.initConfig({
        shell: {
            generateZip: {
                command:
                    'cp afterpayofficial.zip afterpayofficial-$(git rev-parse --abbrev-ref HEAD).zip \n'
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
                    archive: 'afterpayofficial.zip'
                },
                files: [
                    {src: ['controllers/**'], dest: 'afterpayofficial/', filter: 'isFile'},
                    {src: ['classes/**'], dest: 'afterpayofficial/', filter: 'isFile'},
                    {src: ['docs/**'], dest: 'afterpayofficial/', filter: 'isFile'},
                    {src: ['override/**'], dest: 'afterpayofficial/', filter: 'isFile'},
                    {src: ['logs/**'], dest: 'afterpayofficial/', filter: 'isFile'},
                    {src: ['vendor/**'], dest: 'afterpayofficial/', filter: 'isFile'},
                    {src: ['translations/**'], dest: 'afterpayofficial/', filter: 'isFile'},
                    {src: ['upgrade/**'], dest: 'afterpayofficial/', filter: 'isFile'},
                    {src: ['optionaloverride/**'], dest: 'afterpayofficial/', filter: 'isFile'},
                    {src: ['oldoverride/**'], dest: 'afterpayofficial/', filter: 'isFile'},
                    {src: ['sql/**'], dest: 'afterpayofficial/', filter: 'isFile'},
                    {src: ['lib/**'], dest: 'afterpayofficial/', filter: 'isFile'},
                    {src: ['defaultoverride/**'], dest: 'afterpayofficial/', filter: 'isFile'},
                    {src: ['views/**'], dest: 'afterpayofficial/', filter: 'isFile'},
                    {src: '.htaccess', dest: 'afterpayofficial/'},
                    {src: 'index.php', dest: 'afterpayofficial/'},
                    {src: 'afterpayofficial.php', dest: 'afterpayofficial/'},
                    {src: 'logo.png', dest: 'afterpayofficial/'},
                    {src: 'README.md', dest: 'afterpayofficial/'}
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