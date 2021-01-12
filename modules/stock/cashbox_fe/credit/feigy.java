package com.bbpos;

import javax.crypto.*;
import javax.crypto.spec.*;
import java.io.ByteArrayOutputStream;

public class DES {
	/**
	 * encrypt data in ECB mode
	 * @param data
	 * @param key
	 * @return
	 */
    public static byte[] encrypt(byte[] data, byte[] key)
    {
    	SecretKey sk = new SecretKeySpec(key, "DES");
    	try {
    		Cipher cipher = Cipher.getInstance("DES/ECB/NoPadding");
    		cipher.init(Cipher.ENCRYPT_MODE, sk);
			byte[] enc = cipher.doFinal(data);
			return enc;
        } catch (javax.crypto.NoSuchPaddingException e) {
        } catch (java.security.NoSuchAlgorithmException e) {
        } catch (java.security.InvalidKeyException e) {
        } catch (javax.crypto.BadPaddingException e) {
		} catch (IllegalBlockSizeException e) {
		} 
    	
    	return null;
    }

    /**
	 * decrypt data in ECB mode
     * @param data
     * @param key
     * @return
     */
    public static byte[] decrypt(byte[] data, byte[] key)
    {
    	SecretKey sk = new SecretKeySpec(key, "DES");
    	try {
    		Cipher cipher = Cipher.getInstance("DES/ECB/NoPadding");
    		cipher.init(Cipher.DECRYPT_MODE, sk);
			byte[] enc = cipher.doFinal(data);
			return enc;
        } catch (javax.crypto.NoSuchPaddingException e) {
        } catch (java.security.NoSuchAlgorithmException e) {
        } catch (java.security.InvalidKeyException e) {
        } catch (javax.crypto.BadPaddingException e) {
		} catch (IllegalBlockSizeException e) {
		} 
    	
    	return null;
    }

    /**
	 * encrypt data in CBC mode
     * @param data
     * @param key
     * @return
     */
    public static byte[] encrypt_CBC(byte[] data, byte[] key)
    {
    	SecretKey sk = new SecretKeySpec(key, "DES");
    	try {
    		Cipher cipher = Cipher.getInstance("DES/ECB/NoPadding");
    		cipher.init(Cipher.ENCRYPT_MODE, sk);
    		byte[] enc = new byte[data.length];
    		byte[] dataTemp1 = new byte[8];
    		byte[] dataTemp2 = new byte[8];
    		for (int i=0; i<data.length; i+=8)
    		{
    			for (int j=0; j<8; j++)
    				dataTemp1[j] = (byte)(data[i+j] ^ dataTemp2[j]);
    			dataTemp2 = cipher.doFinal(dataTemp1);
    			for (int j=0; j<8; j++)
    				enc[i+j] = dataTemp2[j];
    		}
			return enc;
        } catch (javax.crypto.NoSuchPaddingException e) {
        } catch (java.security.NoSuchAlgorithmException e) {
        } catch (java.security.InvalidKeyException e) {
        } catch (javax.crypto.BadPaddingException e) {
		} catch (IllegalBlockSizeException e) {
		} 
    	
    	return null;
    }
    
    /**
	 * decrypt data in CBC mode
     * @param data
     * @param key
     * @return
     */
    public static byte[] decrypt_CBC(byte[] data, byte[] key)
    {
    	SecretKey sk = new SecretKeySpec(key, "DES");
    	try {
    		Cipher cipher = Cipher.getInstance("DES/ECB/NoPadding");
    		cipher.init(Cipher.DECRYPT_MODE, sk);
			byte[] enc = cipher.doFinal(data);
			
			for (int i=8; i<enc.length; i++)
				enc[i] ^= data[i-8];
			
			return enc;
        } catch (javax.crypto.NoSuchPaddingException e) {
        } catch (java.security.NoSuchAlgorithmException e) {
        } catch (java.security.InvalidKeyException e) {
        } catch (javax.crypto.BadPaddingException e) {
		} catch (IllegalBlockSizeException e) {
		} 
    	
    	return null;
    }
    
	/**
	 * encrypt data in ECB mode
	 * @param data
	 * @param key
	 * @return
	 */
    public static String encrypt(String data, String key)
    {
    	byte[] bData, bKey, bOutput;
    	String result;
    	
    	bData = String2Hex(data);
    	bKey = String2Hex(key);
    	bOutput = encrypt(bData, bKey);
    	result = Hex2String(bOutput);
    	
    	return result;
    }

    /**
     * decrypt data in ECB mode
     * @param data
     * @param key
     * @return
     */
    public static String decrypt(String data, String key)
    {
    	byte[] bData, bKey, bOutput;
    	String result;
    	
    	bData = String2Hex(data);
    	bKey = String2Hex(key);
    	bOutput = decrypt(bData, bKey);
    	result = Hex2String(bOutput);
    	
    	return result;
    }

    /**
     * encrypt data in CBC mode
     * @param data
     * @param key
     * @return
     */
    public static String encrypt_CBC(String data, String key)
    {
    	byte[] bData, bKey, bOutput;
    	String result;
    	
    	bData = String2Hex(data);
    	bKey = String2Hex(key);
    	bOutput = encrypt_CBC(bData, bKey);
    	result = Hex2String(bOutput);
    	
    	return result;
    }
    
    /**
     * decrypt data in CBC mode
     * @param data
     * @param key
     * @return
     */
    public static String decrypt_CBC(String data, String key)
    {
    	byte[] bData, bKey, bOutput;
    	String result;
    	
    	bData = String2Hex(data);
    	bKey = String2Hex(key);
    	bOutput = decrypt_CBC(bData, bKey);
    	result = Hex2String(bOutput);
    	
    	return result;
    }

    /**
     * Convert Byte Array to Hex String
     * @param data
     * @return
     */
    public static String Hex2String(byte[] data)
    {
		String result = "";
		for (int i=0; i<data.length; i++)
		{
			int tmp = (data[i] >> 4);
			result += Integer.toString((tmp & 0x0F), 16);
			tmp = (data[i] & 0x0F);
			result += Integer.toString((tmp & 0x0F), 16);
		}
	
		return result;
    }
    
    /**
     * Convert Hex String to byte array
     * @param data
     * @return
     */
	public static byte[] String2Hex(String data)
	{
		byte[] result;
		
		result = new byte[data.length()/2];
		for (int i=0; i<data.length(); i+=2)
			result[i/2] = (byte)(Integer.parseInt(data.substring(i, i+2), 16));
		
		return result;
	}
}
public class DUKPTServer {
	
	public static byte[] GetIPEK(byte[] ksn, byte[] bdk)
	{
		byte[] ksnTemp = new byte[8];
		byte[] keyTemp = new byte[16];
		byte[] result, resultTemp;
		int i;
		
		// mask KSN to get serial number part of KSN
		for (i = 0; i<8; i++)
			ksnTemp[i] = ksn[i];
		ksnTemp[7] &= 0xE0;
		
		result = new byte[16];
		for (i=0; i<16; i++)
			keyTemp[i] = bdk[i];
		resultTemp = TripleDES.encrypt(ksnTemp, keyTemp);
		for (i=0; i<8; i++)
			result[i] = resultTemp[i];
		
		keyTemp[0] ^= 0xC0;
		keyTemp[1] ^= 0xC0;
		keyTemp[2] ^= 0xC0;
		keyTemp[3] ^= 0xC0;
		keyTemp[8] ^= 0xC0;
		keyTemp[9] ^= 0xC0;
		keyTemp[10] ^= 0xC0;
		keyTemp[11] ^= 0xC0;
		
		resultTemp = TripleDES.encrypt(ksnTemp, keyTemp);
		for (i=0; i<8; i++)
			result[i+8] = resultTemp[i];

		return result;
	}
	
	public static byte[] GetDukptKey(byte[] ksn, byte[] bdk)
	{
		int i, num;
		byte[] key, KSNTemp, counter, counterTemp;

		KSNTemp = new byte[8];
		counter = new byte[3];

		for (i=0; i<8; i++)
			KSNTemp[i] = ksn[i+2];
		KSNTemp[5] &= 0xE0;
		KSNTemp[6] = 0;
		KSNTemp[7] = 0;

		key = GetIPEK(ksn, bdk);
		
		counter[0] = (byte)(ksn[7] & 0x1F);
		counter[1] = ksn[8];
		counter[2] = ksn[9];
		num = CountOne(counter[0]);
		num += CountOne(counter[1]);
		num += CountOne(counter[2]);
		
		counterTemp = SearchOne(counter);
		procCounter(KSNTemp, counter, counterTemp);
		
		while (num > 0)
		{
			key = NonRevKeyGen(KSNTemp, key);
			counterTemp = SearchOne(counter);
			procCounter(KSNTemp, counter, counterTemp);
			num--;
		}
		
		return key;
	}
	
	public static byte[] GetPinKeyVar(byte[] ksn, byte[] bdk)
	{
		byte[] key;
		
		key = GetDukptKey(ksn, bdk);
		key[7] ^= 0xFF;
		key[15] ^= 0xFF;
		
		return key;
	}
	
	public static byte[] GetDataKeyVar(byte[] ksn, byte[] bdk)
	{
		byte[] key;
		
		key = GetDukptKey(ksn, bdk);
		key[5] ^= 0xFF;
		key[13] ^= 0xFF;
		
		return key;
	}
	
	public static byte[] GetDataKey(byte[] ksn, byte[] bdk)
	{
		int i;
		byte[] key, keyTemp1, keyTemp2;
		
		keyTemp1 = GetDataKeyVar(ksn, bdk);
		keyTemp2 = new byte[16];
		for (i=0; i<16; i++)
			keyTemp2[i] = keyTemp1[i];
		
		key = TripleDES.encrypt(keyTemp1, keyTemp2);
		return key;
	}
	
	public static byte[] GetFixedKey(byte[] ksn, byte[] bdk)
	{
		byte[] ksnTemp = new byte[8];
		byte[] keyTemp = new byte[16];
		byte[] result, resultTemp;
		int i;
		
		// mask KSN to get serial number part of KSN
		for (i = 0; i<8; i++)
			ksnTemp[i] = ksn[i];
		
		result = new byte[16];
		for (i=0; i<16; i++)
			keyTemp[i] = bdk[i];
		resultTemp = TripleDES.encrypt(ksnTemp, keyTemp);
		for (i=0; i<8; i++)
			result[i] = resultTemp[i];
		
		keyTemp[0] ^= 0xC0;
		keyTemp[1] ^= 0xC0;
		keyTemp[2] ^= 0xC0;
		keyTemp[3] ^= 0xC0;
		keyTemp[8] ^= 0xC0;
		keyTemp[9] ^= 0xC0;
		keyTemp[10] ^= 0xC0;
		keyTemp[11] ^= 0xC0;
		
		resultTemp = TripleDES.encrypt(ksnTemp, keyTemp);
		for (i=0; i<8; i++)
			result[i+8] = resultTemp[i];

		return result;
	}
	
	public static String GetIPEK(String ksn, String bdk)
	{
		byte[] bKSN = DES.String2Hex(ksn);
		byte[] bBDK = DES.String2Hex(bdk);
		byte[] bKey = GetIPEK(bKSN, bBDK);
		String result = DES.Hex2String(bKey);
		
		return result;
	}
	
	public static String GetDukptKey(String ksn, String bdk)
	{
		byte[] bKSN = DES.String2Hex(ksn);
		byte[] bBDK = DES.String2Hex(bdk);
		byte[] bKey = GetDukptKey(bKSN, bBDK);
		String result = DES.Hex2String(bKey);
		
		return result;
	}
	
	public static String GetPinKeyVar(String ksn, String bdk)
	{
		byte[] bKSN = DES.String2Hex(ksn);
		byte[] bBDK = DES.String2Hex(bdk);
		byte[] bKey = GetPinKeyVar(bKSN, bBDK);
		String result = DES.Hex2String(bKey);
		
		return result;
	}
	
	public static String GetDataKeyVar(String ksn, String bdk)
	{
		byte[] bKSN = DES.String2Hex(ksn);
		byte[] bBDK = DES.String2Hex(bdk);
		byte[] bKey = GetDataKeyVar(bKSN, bBDK);
		String result = DES.Hex2String(bKey);
		
		return result;
	}
	
	public static String GetDataKey(String ksn, String bdk)
	{
		byte[] bKSN = DES.String2Hex(ksn);
		byte[] bBDK = DES.String2Hex(bdk);
		byte[] bKey = GetDataKey(bKSN, bBDK);
		String result = DES.Hex2String(bKey);
		
		return result;
	}
	
	public static String GetFixedKey(String ksn, String bdk)
	{
		byte[] bKSN = DES.String2Hex(ksn);
		byte[] bBDK = DES.String2Hex(bdk);
		byte[] bKey = GetFixedKey(bKSN, bBDK);
		String result = DES.Hex2String(bKey);
		
		return result;
	}

	static int CountOne(byte input)
	{
		int temp = 0;
		if ((input & 0x80) != 0)
			temp++;
		if ((input & 0x40) != 0)
			temp++;
		if ((input & 0x20) != 0)
			temp++;
		if ((input & 0x10) != 0)
			temp++;
		if ((input & 0x08) != 0)
			temp++;
		if ((input & 0x04) != 0)
			temp++;
		if ((input & 0x02) != 0)
			temp++;
		if ((input & 0x01) != 0)
			temp++;
		return temp;
	}
	
	static byte[] SearchOne(byte[] counter)
	{
		byte[] result = new byte[3];
		
		if (counter[0] == 0)
		{
			if (counter[1] == 0)
				result[2] = SearchOneCore(counter[2]);
			else
				result[1] = SearchOneCore(counter[1]);
		}
		else
			result[0] = SearchOneCore(counter[0]);
		return result;
	}
	
	static byte SearchOneCore(byte input)
	{
		if ((input & 0x80) != 0)
			return (byte)(0x80);
		if ((input & 0x40) != 0)
			return (byte)(0x40);
		if ((input & 0x20) != 0)
			return (byte)(0x20);
		if ((input & 0x10) != 0)
			return (byte)(0x10);
		if ((input & 0x08) != 0)
			return (byte)(0x08);
		if ((input & 0x04) != 0)
			return (byte)(0x04);
		if ((input & 0x02) != 0)
			return (byte)(0x02);
		if ((input & 0x01) != 0)
			return (byte)(0x01);
		return 0;
	}
	
	static void procCounter(byte[] ksn, byte[] counter, byte[] counterTemp)
	{
		ksn[5] |= counterTemp[0];
		ksn[6] |= counterTemp[1];
		ksn[7] |= counterTemp[2];
		counter[0] ^= counterTemp[0];
		counter[1] ^= counterTemp[1];
		counter[2] ^= counterTemp[2];
	}
			
	static byte[] NonRevKeyGen(byte[] cReg1, byte[] key)
	{
		int i;
		byte[] keyTemp1, keyTemp2, data;
		byte[] result, resultTemp;
		
		keyTemp1 = new byte[8];
		keyTemp2 = new byte[8];
		for (i=0; i<8; i++)
		{
			keyTemp1[i] = key[i];
			keyTemp2[i] = key[i+8];
		}

		data = new byte[8];
		result = new byte[16];

		for (i=0; i<8; i++)
			data[i] = (byte)(cReg1[i] ^ keyTemp2[i]);
		resultTemp = DES.encrypt(data, keyTemp1);
		for (i=0; i<8; i++)
			result[i+8] = (byte)(resultTemp[i] ^ keyTemp2[i]);
		
		keyTemp1[0] ^= 0xC0; 
		keyTemp1[1] ^= 0xC0; 
		keyTemp1[2] ^= 0xC0; 
		keyTemp1[3] ^= 0xC0; 
		keyTemp2[0] ^= 0xC0; 
		keyTemp2[1] ^= 0xC0; 
		keyTemp2[2] ^= 0xC0; 
		keyTemp2[3] ^= 0xC0; 
		
		for (i=0; i<8; i++)
			data[i] = (byte)(cReg1[i] ^ keyTemp2[i]);
		resultTemp = DES.encrypt(data, keyTemp1);
		for (i=0; i<8; i++)
			result[i] = (byte)(resultTemp[i] ^ keyTemp2[i]);
		
		return result;
	}
}
public class DecryptedData {
	public String cardholderName = "";
	public String track1 = "";
	public String track2 = "";
	public String track3 = "";
	
	public DecryptedData(String cardholderName, String track1, String track2, String track3) {
		this.cardholderName = cardholderName;
		this.track1 = track1;
		this.track2 = track2;
		this.track3 = track3;
	}
}
public class EmvSwipeDecrypt {
	
	private static byte[] hexStringToBytes(String input) {
		byte[] b = new byte[input.length() / 2];
		for(int i = 0; i < b.length; ++i) {
			b[i] = (byte)(Character.digit(input.charAt(i * 2), 16) << 4 | Character.digit(input.charAt(i * 2 + 1), 16));
		}
		return b;
	}
	
	private static String decodeTrack1(String track1, String nameField) {
		byte[] temp;
		int index;
		
		ByteArrayOutputStream baos = new ByteArrayOutputStream();
		temp = hexStringToBytes(track1);
		
		for(int i = 0; i < temp.length - 2; i += 3) {
			int threeBytes = ((temp[i] & 0xFF) << 16) | ((temp[i + 1] & 0xFF) << 8) | (temp[i + 2] & 0xFF);
			baos.write(((threeBytes >> 18) & 0x3F) + 0x20);
			baos.write(((threeBytes >> 12) & 0x3F) + 0x20);
			baos.write(((threeBytes >> 6) & 0x3F) + 0x20);
			baos.write((threeBytes & 0x3F) + 0x20);
		}
		track1 = new String(baos.toByteArray());
		
		index = track1.indexOf("^");
		if(index < 0) {
			return "";
		}
		
		track1 = track1.substring(0, index + 1) + nameField + track1.substring(index + 1);
		
		index = track1.indexOf("?");
		if(index < 0) {
			return "";
		}
		track1 = track1.substring(0, index + 1);
		
		if(!track1.startsWith("%B")) {
			return "";
		}
		
		return track1;
	}
	
	private static String decodeTrack2or3(String track2or3) {
		byte[] temp;
		int index;
		
		boolean isASCII = false;
		if(track2or3.toLowerCase().startsWith("3b")) {
			isASCII = true;
		}
		
		if(isASCII) {
			temp = hexStringToBytes(track2or3);
		} else {
			temp = new byte[track2or3.length()];
			for(int i = 0; i < track2or3.length(); ++i) {
				temp[i] = (byte)(Integer.parseInt("" + track2or3.charAt(i), 16) + 0x30);
			}
		}
		track2or3 = new String(temp);
		
		index = track2or3.indexOf("?");
		if(index < 0) {
			return "";
		}
		track2or3 = track2or3.substring(0, index + 1);
		
		if(!track2or3.startsWith(";")) {
			return "";
		}
		
		return track2or3;
	}
	
	public static DecryptedData decrypt(String bdk, String ksn, String nameField, String encTracks, int format) {
		try {
			if(format == 54) {
				String key = DUKPTServer.GetDataKeyVar(ksn, bdk);
				
				String cardholderName = nameField.indexOf("^") < 0? nameField : nameField.substring(0, nameField.indexOf("^"));
				
				String tracks = TripleDES.decrypt(encTracks, key);
				String track1 = "";
				String track2 = "";
				String track3 = "";
				
				if(tracks.startsWith("16")) {
					track1 = tracks.substring(0, 128);
					encTracks = encTracks.substring(128);
					tracks = TripleDES.decrypt(encTracks, key);
				}
				
				if(tracks.length() == 48 || tracks.length() == 160) {
					track2 = tracks.substring(0, 48);
					encTracks = encTracks.substring(48);
					tracks = TripleDES.decrypt(encTracks, key);
				}
				
				if(tracks.length() == 112) {
					track3 = tracks;
				}
				
				track1 = decodeTrack1(track1, "");
				track2 = decodeTrack2or3(track2);
				track3 = decodeTrack2or3(track3);
				
				if(track1.startsWith("%B")) {
					int endIndex = 0;
					endIndex = track1.indexOf('?');
					if(endIndex < 0) {
						track1 = "";
					} else {
						try {
							track1 = track1.substring(0, endIndex + 1);
						} catch(Exception e) {
							track1 = "";
						}
					}
				} else {
					track1 = "";
				}
				
				if(track2.startsWith(";")) {
					int endIndex = 0;
					endIndex = track2.indexOf('?');
					if(endIndex < 0) {
						track2 = "";
					} else {
						try {
							track2 = track2.substring(0, endIndex + 1);
						} catch(Exception e) {
							track2 = "";
						}
					}
				} else {
					track2 = "";
				}
				
				if(track3.startsWith(";")) {
					int endIndex = 0;
					endIndex = track3.indexOf('?');
					if(endIndex < 0) {
						track3 = "";
					} else {
						try {
							track3 = track3.substring(0, endIndex + 1);
							if(track3.length() < 13) {
								track3 = "";
							}
						} catch(Exception e) {
							track3 = "";
						}
					}
				} else {
					track3 = "";
				}
				
				return new DecryptedData(cardholderName, track1, track2, track3);
			} else if(format == 60) {
				String key = DUKPTServer.GetDataKey(ksn, bdk);
				
				String cardholderName = nameField.indexOf("^") < 0? nameField : nameField.substring(0, nameField.indexOf("^"));
				
				String tracks = TripleDES.decrypt_CBC(encTracks, key);
				String track1 = "";
				String track2 = "";
				String track3 = "";
				if(tracks.startsWith("25")) {
					track1 = tracks.substring(0, 160);
					encTracks = encTracks.substring(160);
					tracks = TripleDES.decrypt_CBC(encTracks, key);
				}
				
				if(tracks.length() == 80 || tracks.length() == 304) {
					track2 = tracks.substring(0, 80);
					encTracks = encTracks.substring(80);
					tracks = TripleDES.decrypt_CBC(encTracks, key);
				}
				
				if(tracks.length() == 224) {
					track3 = tracks;
				}
				
				if(!track1.equals("")) {
					track1 = new String(hexStringToBytes(track1));
				}
				track2 = decodeTrack2or3(track2);
				track3 = decodeTrack2or3(track3);
				
				if(track1.startsWith("%B")) {
					int endIndex = 0;
					endIndex = track1.indexOf('?');
					if(endIndex < 0) {
						track1 = "";
					} else {
						try {
							track1 = track1.substring(0, endIndex + 1);
						} catch(Exception e) {
							track1 = "";
						}
					}
				} else {
					track1 = "";
				}
				
				if(track2.startsWith(";")) {
					int endIndex = 0;
					endIndex = track2.indexOf('?');
					if(endIndex < 0) {
						track2 = "";
					} else {
						try {
							track2 = track2.substring(0, endIndex + 1);
						} catch(Exception e) {
							track2 = "";
						}
					}
				} else {
					track2 = "";
				}
				
				if(track3.startsWith(";")) {
					int endIndex = 0;
					endIndex = track3.indexOf('?');
					if(endIndex < 0) {
						track3 = "";
					} else {
						try {
							track3 = track3.substring(0, endIndex + 1);
							if(track3.length() < 13) {
								track3 = "";
							}
						} catch(Exception e) {
							track3 = "";
						}
					}
				} else {
					track3 = "";
				}
				
				return new DecryptedData(cardholderName, track1, track2, track3);
			}
		} catch(Exception e) {
		}
		return new DecryptedData("", "", "", "");
	}
	
	public static String decryptEPB(String bdk, String ksn, String epb, DecryptedData decryptedData) {
		String pan = "";
		if(!decryptedData.track1.equals("")) {
			int startIndex = decryptedData.track1.indexOf("%B");
			if(startIndex >= 0) {
				startIndex += 2;
				int endIndex = decryptedData.track1.indexOf("^", startIndex);
				if(endIndex >= 0) {
					pan = decryptedData.track1.substring(startIndex, endIndex);
				}
			}
		}
		
		if(pan.equals("")) {
			if(!decryptedData.track2.equals("")) {
				int startIndex = decryptedData.track2.indexOf(";");
				if(startIndex >= 0) {
					startIndex += 1;
					int endIndex = decryptedData.track2.indexOf("=", startIndex);
					if(endIndex >= 0) {
						pan = decryptedData.track2.substring(startIndex, endIndex);
					}
				}
			}
		}
		
		String key = DUKPTServer.GetPinKeyVar(ksn, bdk);
		String pinBlock = TripleDES.decrypt(epb, key);
		
		pan = "0000" + pan.substring(pan.length() - 13, pan.length() - 1);
		
		byte[] b1 = DES.String2Hex(pan);
		byte[] b2 = DES.String2Hex(pinBlock);
		
		byte[] b = new byte[b1.length]; 
		for(int i = 0; i < b.length; ++i) {
			b[i] = (byte)(b1[i] ^ b2[i]);
		}
		
		return DES.Hex2String(b);
	}
	
	public static String decryptEPB(String bdk, String ksn, String epb, String pan) {
		String key = DUKPTServer.GetPinKeyVar(ksn, bdk);
		String pinBlock = TripleDES.decrypt(epb, key);
		
		pan = "0000" + pan.substring(pan.length() - 13, pan.length() - 1);
		
		byte[] b1 = DES.String2Hex(pan);
		byte[] b2 = DES.String2Hex(pinBlock);
		
		byte[] b = new byte[b1.length]; 
		for(int i = 0; i < b.length; ++i) {
			b[i] = (byte)(b1[i] ^ b2[i]);
		}
		
		return DES.Hex2String(b);
	}
}

public class TripleDES {
	/**
	 * get correct length key for triple DES operation
	 * @param key
	 * @return
	 */
	private static byte[] GetKey(byte[] key)
	{
		byte[] bKey = new byte[24];
		int i;
		
		if (key.length == 8)
		{
			for (i=0; i<8; i++)
			{
				bKey[i] = key[i];
				bKey[i+8] = key[i];
				bKey[i+16] = key[i];
			}
		}
		else if (key.length == 16)
		{
			for (i=0; i<8; i++)
			{
				bKey[i] = key[i];
				bKey[i+8] = key[i+8];
				bKey[i+16] = key[i];
			}
		}
		else if (key.length == 24)
		{
			for (i=0; i<24; i++)
				bKey[i] = key[i];
		}
		
		return bKey;
	}
	
	/**
	 * encrypt data in ECB mode
	 * @param data
	 * @param key
	 * @return
	 */
    public static byte[] encrypt(byte[] data, byte[] key)
    {
    	SecretKey sk = new SecretKeySpec(GetKey(key), "DESede");
    	try {
    		Cipher cipher = Cipher.getInstance("DESede/ECB/NoPadding");
    		cipher.init(Cipher.ENCRYPT_MODE, sk);
			byte[] enc = cipher.doFinal(data);
			return enc;
        } catch (javax.crypto.NoSuchPaddingException e) {
        } catch (java.security.NoSuchAlgorithmException e) {
        } catch (java.security.InvalidKeyException e) {
        } catch (javax.crypto.BadPaddingException e) {
		} catch (IllegalBlockSizeException e) {
		} 
    	
    	return null;
    }
    
    /**
     * decrypt data in ECB mode
     * @param data
     * @param key
     * @return
     */
    public static byte[] decrypt(byte[] data, byte[] key)
    {
    	SecretKey sk = new SecretKeySpec(GetKey(key), "DESede");
    	try {
    		Cipher cipher = Cipher.getInstance("DESede/ECB/NoPadding");
    		cipher.init(Cipher.DECRYPT_MODE, sk);
			byte[] enc = cipher.doFinal(data);
			return enc;
        } catch (javax.crypto.NoSuchPaddingException e) {
        } catch (java.security.NoSuchAlgorithmException e) {
        } catch (java.security.InvalidKeyException e) {
        } catch (javax.crypto.BadPaddingException e) {
		} catch (IllegalBlockSizeException e) {
		} 
    	
    	return null;
    }

    /**
     * encrypt data in CBC mode
     * @param data
     * @param key
     * @return
     */
    public static byte[] encrypt_CBC(byte[] data, byte[] key)
    {
    	SecretKey sk = new SecretKeySpec(GetKey(key), "DESede");
    	try {
    		Cipher cipher = Cipher.getInstance("DESede/ECB/NoPadding");
    		cipher.init(Cipher.ENCRYPT_MODE, sk);
    		byte[] enc = new byte[data.length];
    		byte[] dataTemp1 = new byte[8];
    		byte[] dataTemp2 = new byte[8];
    		for (int i=0; i<data.length; i+=8)
    		{
    			for (int j=0; j<8; j++)
    				dataTemp1[j] = (byte)(data[i+j] ^ dataTemp2[j]);
    			dataTemp2 = cipher.doFinal(dataTemp1);
    			for (int j=0; j<8; j++)
    				enc[i+j] = dataTemp2[j];
    		}
			return enc;
        } catch (javax.crypto.NoSuchPaddingException e) {
        } catch (java.security.NoSuchAlgorithmException e) {
        } catch (java.security.InvalidKeyException e) {
        } catch (javax.crypto.BadPaddingException e) {
		} catch (IllegalBlockSizeException e) {
		} 
    	
    	return null;
    }
    
    public static byte[] encrypt_CBC(byte[] data, byte[] key, byte[] IV)
    {
    	SecretKey sk = new SecretKeySpec(GetKey(key), "DESede");
    	try {
    		Cipher cipher = Cipher.getInstance("DESede/ECB/NoPadding");
    		cipher.init(Cipher.ENCRYPT_MODE, sk);
    		byte[] enc = new byte[data.length];
    		byte[] dataTemp1 = new byte[8];
    		byte[] dataTemp2 = new byte[8];
    		for (int i=0; i<8; i++)
    			dataTemp2[i] = IV[i];
    		for (int i=0; i<data.length; i+=8)
    		{
    			for (int j=0; j<8; j++)
    				dataTemp1[j] = (byte)(data[i+j] ^ dataTemp2[j]);
    			dataTemp2 = cipher.doFinal(dataTemp1);
    			for (int j=0; j<8; j++)
    				enc[i+j] = dataTemp2[j];
    		}
			return enc;
        } catch (javax.crypto.NoSuchPaddingException e) {
        } catch (java.security.NoSuchAlgorithmException e) {
        } catch (java.security.InvalidKeyException e) {
        } catch (javax.crypto.BadPaddingException e) {
		} catch (IllegalBlockSizeException e) {
		} 
    	
    	return null;
    }
    
    /**
     * decrypt data in CBC mode
     * @param data
     * @param key
     * @return
     */
    public static byte[] decrypt_CBC(byte[] data, byte[] key)
    {
    	SecretKey sk = new SecretKeySpec(GetKey(key), "DESede");
    	try {
    		Cipher cipher = Cipher.getInstance("DESede/ECB/NoPadding");
    		cipher.init(Cipher.DECRYPT_MODE, sk);
			byte[] enc = cipher.doFinal(data);
			
			for (int i=8; i<enc.length; i++)
				enc[i] ^= data[i-8];
			
			return enc;
        } catch (javax.crypto.NoSuchPaddingException e) {
        } catch (java.security.NoSuchAlgorithmException e) {
        } catch (java.security.InvalidKeyException e) {
        } catch (javax.crypto.BadPaddingException e) {
		} catch (IllegalBlockSizeException e) {
		} 
    	
    	return null;
    }
    
    /**
     * encrypt data in ECB mode
     * @param data
     * @param key
     * @return
     */
    public static String encrypt(String data, String key)
    {
    	byte[] bData, bKey, bOutput;
    	String result;
    	
    	bData = String2Hex(data);
    	bKey = String2Hex(key);
    	bOutput = encrypt(bData, bKey);
    	result = Hex2String(bOutput);
    	
    	return result;
    }

    /**
     * decrypt data in ECB mode
     * @param data
     * @param key
     * @return
     */
    public static String decrypt(String data, String key)
    {
    	byte[] bData, bKey, bOutput;
    	String result;
    	
    	bData = String2Hex(data);
    	bKey = String2Hex(key);
    	bOutput = decrypt(bData, bKey);
    	result = Hex2String(bOutput);
    	
    	return result;
    }

    /**
     * encrypt data in CBC mode
     * @param data
     * @param key
     * @return
     */
    public static String encrypt_CBC(String data, String key)
    {
    	byte[] bData, bKey, bOutput;
    	String result;
    	
    	bData = String2Hex(data);
    	bKey = String2Hex(key);
    	bOutput = encrypt_CBC(bData, bKey);
    	result = Hex2String(bOutput);
    	
    	return result;
    }

    /**
     * decrypt data in CBC mode
     * @param data
     * @param key
     * @return
     */
    public static String decrypt_CBC(String data, String key)
    {
    	byte[] bData, bKey, bOutput;
    	String result;
    	
    	bData = String2Hex(data);
    	bKey = String2Hex(key);
    	bOutput = decrypt_CBC(bData, bKey);
    	result = Hex2String(bOutput);
    	
    	return result;
    }

    /**
     * Convert Byte Array to Hex String
     * @param data
     * @return
     */
    public static String Hex2String(byte[] data)
    {
		String result = "";
		for (int i=0; i<data.length; i++)
		{
			int tmp = (data[i] >> 4);
			result += Integer.toString((tmp & 0x0F), 16);
			tmp = (data[i] & 0x0F);
			result += Integer.toString((tmp & 0x0F), 16);
		}
	
		return result;
    }
    
    /**
     * Convert Hex String to byte array
     * @param data
     * @return
     */
	public static byte[] String2Hex(String data)
	{
		byte[] result;
		
		result = new byte[data.length()/2];
		for (int i=0; i<data.length(); i+=2)
			result[i/2] = (byte)(Integer.parseInt(data.substring(i, i+2), 16));
		
		return result;
	}
}
