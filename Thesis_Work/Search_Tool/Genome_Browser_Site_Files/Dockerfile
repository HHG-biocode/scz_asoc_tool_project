FROM php:8.0-apache as base
FROM ubuntu:trusty
RUN sudo apt-get -y update
RUN sudo apt-get -y upgrade
RUN sudo apt-get install -y sqlite3 libsqlite3-dev
RUN apt-get install nano 
RUN apt-get update nano
RUN /usr/bin/sqlite3 /html/db/scz_asoc.db
CMD /bin/bash
