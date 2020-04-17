# Suitecrm openshift template 

SuiteCRM is the award-winning open-source, enterprise-ready Customer Relationship Management (CRM) software application. [https://suitecrm.com](https://suitecrm.com)

This is the openshift deployment template and scripts for Suitecrm </a>. It was built using the <a href="https://github.com/salesagility/SuiteCRM/releases">latest Suitecrm release</a>.

Also provides quick and easy upgrade Suitecrm to newer release when ever it is avaialble. 

## How to use this template

To run this template you will need an existing project on openshift. 

### Prepare your project 

```
oc login https://console.myopenshifturl.com

oc new-project suitecrmdev --description="Suitecrm dev open source crm"   --display-name="Suitecrm-dev"
oc policy add-role-to-user edit system:serviceaccount:suitecrmdev:default   -n suitecrmdev
oc project suitecrmdev
```

Checkout this project 

```
git clone https://github.com/parkarteam/openshift-suitecrm.git

```

## Create app with the template

```
cd openshift-suitecrm 
oc new-app  -f openshift-templates/suitecrm-template-dev.yaml -n suitecrmdev

```
Allow for 15 to 20 mins for the application to be ready. 
The template create an application which can be access at 
https://suitecrm-suitecrmdv.apps.myopenshifturl.com

## Following Objects are created after sucessful deployment  
### Images
1. phpbase:7.3-fpm-alpine 
2. suitecrmdev-basebuild:latest
3. suitecrmdev-build:latest
4. suitecrmdev-mysql
5. suitecrm

### Builds
1. suitecrmdev-basebuild
2. suitecrmdev-build
### Secrets
1. suitecrmdev-secret

### Deployments
1. suitecrm
2. suitecrmdev-mysql

### Services
1. suitecrm
2. suitecrmdev-mysql

### Routes
1. suitecrm

## Upgrading to newer version 
The default version of Suitecrm is Suitecrm-V2.0.15. 
When a new release is available the build can be changed to match the new version. 
For example if you need to use Suitecrm 2.1 Beta , run the below command

```
oc set env bc/leantimedev-build \ 
LEANTIME_RELEASE_URL=https://github.com/salesagility/SuiteCRM/archive/v7.10.25.tar.gz -n suitecrmdev

```

## Customizing the template 
To use custom project names 

```
oc new-app  -f openshift-template/suitecrm-template-dev.yaml --param NAMESPACE=customproject
```

Other parameters 
1. NAMESPACE - The OpenShift Namespace where the ImageStream resides (default value suitecrmdev).
2. NAME -The name assigned to all of the frontend objects defined in this template (default value suitecrm).
3. PHP_VERSION - Version of PHP image to be used (7.2-fpm-alpine or latest). 
4. MEMORY_LIMIT - Maximum amount of memory the SUITECRM container can use (default 512Mi).
5. MEMORY_MYSQL_LIMIT - Maximum amount of memory the MySQL container can use (default 512Mi).
6. SOURCE_REPOSITORY_URL - The URL of the repository with your application source code or a fork of this project. 
7. SOURCE_REPOSITORY_REF - Set this to a branch name, tag or other ref of your repository if you are not using the default branch.
8. CONTEXT_DIR - Set this to the relative path to your project if it is not in the root of your repository.
9. DATABASE_SERVICE_NAME - Database Service Name (default value suitecrmdev-mysql)
10. DATABASE_NAME - Database Name (default value suitecrmdevdb)
11. DATABASE_USER - Databse user (default value suitecrmdevdbuser)
12. DATABASE_PASSWORD - Random Password is generated. 
13. LEANTIME_RELEASE_URL - Suitecrm release url (default version is Suitecrm-v7.11.13). This can be changed after deployment. 

## Debug
This template creates a pod for suitecrm-installer. Locate the actual pod name by runnin oc get pods.
View the detailed logs ''' oc get logs suitecrm-installer-sgdxm ```


