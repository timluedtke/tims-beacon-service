# Adafruit Bibliothek importieren
import Adafruit_DHT

#Sensortyp und GPIO festlegen
sensor = Adafruit_DHT.DHT22
gpio = 4

# Daten auslesen
humid, temp = Adafruit_DHT.read_retry(sensor, gpio)

# Ausgab
print """{{"temperature":{0:0.1f},"humidity":{1:0.1f}}}""".format(temp,humid)