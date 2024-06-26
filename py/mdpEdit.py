#Importation des packages systèmes et email
import sys
import smtplib
from email.mime.text import MIMEText
from email.mime.multipart import MIMEMultipart
from email.mime.application import MIMEApplication

#Si il y a des arguments lors de l'exécution du script
if __name__ == "__main__":
    #Récupération de l'email placé en argument
    email = sys.argv[1]
    #Récupération du code de confirmation
    code = sys.argv[2]

#Mettre le mail récupéré en destinataire
to_mail = [email]

#Mise en place du mail de l'expéditeur
from_mail = 'remi.braem864@gmail.com'

#Création du message
message = "<div style='border: thin solid black; background: lightgrey;'><h1>Code de confirmation : "+ code + "</h1></div>"

#Création de l'objet du mail
objet = 'Demande de changement de mot de passe'

#Parcours de la liste des destinataires
for mail in to_mail:
    #Génération d'un mail
    msg = MIMEMultipart()
    #Ajout des différentes informations   
    msg['Subject'] = objet
    msg['From'] = from_mail
    msg['To'] = mail
    msg.attach(MIMEText(message, 'html'))

    #Envoie du mail
    with smtplib.SMTP('smtp.gmail.com', '587') as smtpserver:
        smtpserver.ehlo()
        smtpserver.starttls()
        smtpserver.ehlo()
        smtpserver.login(from_mail, 'wzou hxmz dpsa bnbl')
    
        smtpserver.send_message(msg)
        

    #Fermeture du serveur SMTP
    smtpserver.close()
