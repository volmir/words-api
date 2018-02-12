#!/bin/bash

for (( i=0; i<=4; i++ ))
do
    for (( j=1; j<=1000; j++ ))
    do
        catalog=$(( $j + ($i * 1000) ))
        wget -r -l1 -E -nc -U YandexBot -P ./$i --reject=gif,jpg,png,js,css,ico,txt http://словарь-толковый.рф/словарь-кузнецова/список-слов/$catalog
    done
done


