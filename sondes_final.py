#!/usr/bin/env python
# -*- coding: utf-8 -*-

import os
import json
import requests

#Definition des sondes

def nom_machine():
    process = os.popen("hostname")
    nom = process.read()
    process.close()
    return nom.rstrip()

def memoire_dispo():
    process = os.popen("cat /proc/meminfo | grep 'MemFree' | awk ' {print $2,$3} '")
    dispo = process.read()
    process.close()
    return dispo.rstrip()


def adresse_mac():
	process = os.popen("/sbin/ifconfig | grep ether | head -1 | awk '{ print $2 }'")
	mac = process.read()
	process.close()
	return mac.rstrip()

def memoire_total():
    process = os.popen("cat /proc/meminfo | grep 'MemTotal' | awk ' {print $2,$3} '")
    total = process.read()
    process.close()
    return total.rstrip()

def nom_processeur():
    process = os.popen("cat /proc/cpuinfo | grep 'model name' | tail -n 1 | cut -d':' -f2")
    nom_proc = process.read()
    process.close()
    return nom_proc.rstrip()

def nom_graphique():
    process = os.popen("lspci| grep VGA | cut -d':' -f3")
    nom_graph = process.read()
    process.close()
    return nom_graph.rstrip()

def temps_actif():
    process = os.popen("uptime | awk ' { print $3 }' |cut -d',' -f1")
    temps = process.read()
    process.close()
    return temps.rstrip()

def disk_max():
    process = os.popen("df -h | grep -w '/' | awk ' { print $2 }'")
    max = process.read()
    process.close()
    return max.rstrip()

def disk_dispo():
    process = os.popen("df -h | grep -w '/'| awk ' { print $4 }'")
    dispo = process.read()
    process.close()
    return dispo.rstrip()

def test_ping():
    process = os.popen("ping 8.8.8.8 -c4")
    dispo = process.read()
    process2 = os.popen("echo $?")
    te = process2.read()
    process.close()
    process2.close()
    return te


#Extraction JSON

machine = {
    "Nom_Machine": nom_machine(),
    "Adresse_Mac": adresse_mac(),
    "Memoire_Installer": memoire_total(),
    "Memoire_Dispo": memoire_dispo(),
    "Nom_Processeur": nom_processeur(),
    "Nom_Graphique": nom_graphique(),
    "Temps_Actif": temps_actif(),
    "Capaciter_Disque": disk_max(),
    "Capaciter_Dispo": disk_dispo()
}

r = requests.post("http://localhost:8000/", json={adresse_mac(): machine})