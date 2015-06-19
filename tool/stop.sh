#!/bin/sh

ps aux |grep wx_reader | grep -v 'grep wx_reader' | awk '{print $2}' | xargs kill -9
