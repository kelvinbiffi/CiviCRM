var gulp = require('gulp-param')(require('gulp'), process.argv),
	concat = require('gulp-concat'),
	less = require('gulp-less'),
	path = require('path'),
	del = require('del'),
	util = require('gulp-util'),
	ftp = require('gulp-ftp'),
	jshint = require('gulp-jshint');

//FTP configuration
var configFTP = function(path){
	return {
		host: 'ftp.xxx.com',
		user: 'xxx',
		pass: 'xxx',
		remotePath: 'public_html/drupal/sites/default/files/civicrm/extensions/org.civicrm.mobule.memberships/' + path
	};
};

gulp.task('pubCRM', function(){
	return gulp.src("CRM/Memberships/Page/*.php")
	.pipe(ftp(configFTP('CRM/Memberships/Page')))
	.pipe(util.noop());
});

gulp.task('pubTemplate', function(){
	return gulp.src("templates/CRM/Memberships/Page/*.tpl")
	.pipe(ftp(configFTP('templates/CRM/Memberships/Page')))
	.pipe(util.noop());
});

gulp.task('pubInfoXML', function(){
	return gulp.src('info.xml')
	.pipe(ftp(configFTP('')))
	.pipe(util.noop());
});

gulp.task('pubXML', ['pubInfoXML'], function(){
	return gulp.src('xml/Menu/*.xml')
	.pipe(ftp(configFTP('xml/Menu')))
	.pipe(util.noop());
});

gulp.task('civixFiles', function(){
	return gulp.src('*.php')
	.pipe(ftp(configFTP('')))
	.pipe(util.noop());
});

//Publish All Source Files
gulp.task('publish', ['civixFiles','pubCRM','pubTemplate','pubXML'], function(){
	console.log("Published");
});
