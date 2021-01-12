package com.bbpos;

public class Main {

	public static void main(String[] args) {
		
		String bdk = "0123456789ABCDEFFEDCBA9876543210";
		String ksn = "02821015140084E00019";
		String nameField = "";
		String encTrack1 = "E215056192C047CB0666BB5F02973AD2EAC3C2C2257651E5C35A32BADCEE4BF88B0039BB0139F8C6243AD911C3B59618A0A8AAC38F4647977FA3D434A8F1D0EA6426A696FE759A0C418FDFF124A3A678";
		String encTrack2 = "7B418AFA2E1E68B84013DE51A7CE87E77125F61A0DBB51BCCABF353A1D6591753F6C0368A4EF4ED9";
		String encTrack3 = "";
		int format = 60;
		
		DecryptedData decryptedData = EmvSwipeDecrypt.decrypt(bdk, ksn, nameField, encTrack1 + encTrack2 + encTrack3, format);
		
		System.out.println("Cardholder Name: " + decryptedData.cardholderName);
		System.out.println("Track 1: " + decryptedData.track1);
		System.out.println("Track 2: " + decryptedData.track2);
		System.out.println("Track 3: " + decryptedData.track3);
	}
	
}
