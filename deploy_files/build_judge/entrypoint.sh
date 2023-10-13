set -xe

if [ ! -d /volume/etc ]; then
    cp -rp /home/judge/etc /volume/etc;
fi

if [ ! -f /volume/etc/judge.conf ]; then
    cp -rp /home/judge/etc/judge.conf /volume/etc/judge.conf;
fi
if [ $OJ_HTTP_BASEURL ];    then sed -i "s#OJ_HTTP_BASEURL=.*#OJ_HTTP_BASEURL=$OJ_HTTP_BASEURL#g"       /volume/etc/judge.conf; fi
if [ $OJ_HTTP_PASSWORD ];   then sed -i "s#OJ_HTTP_PASSWORD=.*#OJ_HTTP_PASSWORD=$OJ_HTTP_PASSWORD#g"    /volume/etc/judge.conf; fi
if [ $JUDGE_PROCESS_NUM ];   then sed -i "s#OJ_RUNNING=.*#OJ_RUNNING=$JUDGE_PROCESS_NUM#g"    /volume/etc/judge.conf; fi

if [ ! -d /volume/data ]; then  
    cp -rp /home/judge/data /volume/data;  
fi 

rm -rf /home/judge/backup   
rm -rf /home/judge/data 
rm -rf /home/judge/etc
ln -s /volume/data   /home/judge/data   
ln -s /volume/etc    /home/judge/etc

RUNNING=`cat /home/judge/etc/judge.conf | grep OJ_RUNNING`
RUNNING=${RUNNING:11}
for i in `seq 1 $RUNNING`; do
    mkdir -p    /home/judge/run`expr ${i} - 1`;
    chown judge /home/judge/run`expr ${i} - 1`;
done

/usr/bin/judged
# judged /home/judge debug
sleep infinity