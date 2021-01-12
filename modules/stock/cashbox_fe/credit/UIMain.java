package com.bbpos;

import java.awt.Color;
import java.awt.event.ActionEvent;
import java.awt.event.ActionListener;

import javax.swing.BorderFactory;
import javax.swing.JButton;
import javax.swing.JComboBox;
import javax.swing.JFrame;
import javax.swing.JLabel;
import javax.swing.JPanel;
import javax.swing.JScrollPane;
import javax.swing.JTextArea;
import javax.swing.JTextField;
import javax.swing.SwingConstants;

public class UIMain extends JFrame {
	
	private JPanel inputPanel;
	private JPanel outputPanel;
	
	private JLabel formatIdLabel;
	private JLabel bdkLabel;
	private JLabel ksnLabel;
	private JLabel nameLabel;
	private JLabel encryptedTracksLabel;
	private JLabel epbLabel;
	private JLabel epbKsnLabel;
	private JLabel cardholderNameLabel;
	private JLabel track1Label;
	private JLabel track2Label;
	private JLabel track3Label;
	private JLabel pinBlockLabel;
	
	private JComboBox<String> formatIdComboBox;
	
	private JTextField bdkField;
	private JTextField ksnField;
	private JTextField nameField;
	private JTextField epbField;
	private JTextField epbKsnField;
	private JTextField cardholderNameField;
	private JTextField pinBlockField;
	
	private JScrollPane encryptedTracksScrollPane;
	private JScrollPane track1ScrollPane;
	private JScrollPane track2ScrollPane;
	private JScrollPane track3ScrollPane;
	
	private JTextArea encryptedTracksArea;
	private JTextArea track1Area;
	private JTextArea track2Area;
	private JTextArea track3Area;
	
	private JButton decryptButton;

	public UIMain() {
		setLayout(null);
		
		inputPanel = new JPanel();
		outputPanel = new JPanel();
		
		formatIdLabel = new JLabel("Format ID", SwingConstants.RIGHT);
		bdkLabel = new JLabel("BDK", SwingConstants.RIGHT);
		ksnLabel = new JLabel("KSN", SwingConstants.RIGHT);
		nameLabel = new JLabel("Name", SwingConstants.RIGHT);
		encryptedTracksLabel = new JLabel("Encrypted Tracks", SwingConstants.RIGHT);
		epbLabel = new JLabel("EPB", SwingConstants.RIGHT);
		epbKsnLabel = new JLabel("EPB KSN", SwingConstants.RIGHT);
		cardholderNameLabel = new JLabel("Cardholder Name", SwingConstants.RIGHT);
		track1Label = new JLabel("Track 1", SwingConstants.RIGHT);
		track2Label = new JLabel("Track 2", SwingConstants.RIGHT);
		track3Label = new JLabel("Track 3", SwingConstants.RIGHT);
		pinBlockLabel = new JLabel("PIN Block", SwingConstants.RIGHT);
		
		formatIdComboBox = new JComboBox<String>(new String[] {"54", "60"});
		
		bdkField = new JTextField();
		ksnField = new JTextField();
		nameField = new JTextField();
		epbField = new JTextField();
		epbKsnField = new JTextField();
		cardholderNameField = new JTextField();
		pinBlockField = new JTextField();
		
		encryptedTracksArea = new JTextArea();
		track1Area = new JTextArea();
		track2Area = new JTextArea();
		track3Area = new JTextArea();
		
		encryptedTracksScrollPane = new JScrollPane(encryptedTracksArea);
		track1ScrollPane = new JScrollPane(track1Area);
		track2ScrollPane = new JScrollPane(track2Area);
		track3ScrollPane = new JScrollPane(track3Area);
		
		decryptButton = new JButton("Decrypt");
		
		inputPanel.setLayout(null);
		inputPanel.setBorder(BorderFactory.createTitledBorder("Input"));
		outputPanel.setLayout(null);
		outputPanel.setBorder(BorderFactory.createTitledBorder("Output"));
		encryptedTracksArea.setLineWrap(true);
		track1Area.setLineWrap(true);
		track2Area.setLineWrap(true);
		track3Area.setLineWrap(true);
		decryptButton.addActionListener(new ActionListener() {
			
			@Override
			public void actionPerformed(ActionEvent e) {
				
				String bdk = bdkField.getText().replace(" ", "");
				String ksn = ksnField.getText().replace(" ", "");
				String name = nameField.getText();
				String encryptedTracks = encryptedTracksArea.getText().replace(" ", "");
				int formatId = Integer.parseInt((String)formatIdComboBox.getSelectedItem());
				DecryptedData decryptedData = EmvSwipeDecrypt.decrypt(bdk, ksn, name, encryptedTracks, formatId);
				
				cardholderNameField.setText(decryptedData.cardholderName);
				track1Area.setText(decryptedData.track1);
				track2Area.setText(decryptedData.track2);
				track3Area.setText(decryptedData.track3);
				
				String epb = epbField.getText().replace(" ", "");;
				String epbKsn = epbKsnField.getText().replace(" ", "");;
				
				if(!epb.equals("") && !epbKsn.equals("")) {
					pinBlockField.setText(EmvSwipeDecrypt.decryptEPB(bdk, epbKsn, epb, decryptedData));
				} else {
					pinBlockField.setText("");
				}
				
			}
		});
		
		inputPanel.setBounds(5, 5, 490, 340);
		formatIdLabel.setBounds(5, 25, 110, 25);
		bdkLabel.setBounds(5, 55, 110, 25);
		ksnLabel.setBounds(5, 85, 110, 25);
		nameLabel.setBounds(5, 115, 110, 25);
		encryptedTracksLabel.setBounds(5, 145, 110, 25);
		epbLabel.setBounds(5, 265, 110, 25);
		epbKsnLabel.setBounds(5, 295, 110, 25);
		formatIdComboBox.setBounds(120, 25, 260, 25);
		bdkField.setBounds(120, 55, 260, 25);
		ksnField.setBounds(120, 85, 360, 25);
		nameField.setBounds(120, 115, 360, 25);
		encryptedTracksScrollPane.setBounds(120, 145, 360, 115);
		epbField.setBounds(120, 265, 360, 25);
		epbKsnField.setBounds(120, 295, 360, 25);
		decryptButton.setBounds(385, 25, 95, 55);
		
		outputPanel.setBounds(500, 5, 490, 270);
		cardholderNameLabel.setBounds(5, 25, 110, 25);
		track1Label.setBounds(5, 55, 110, 25);
		track2Label.setBounds(5, 115, 110, 25);
		track3Label.setBounds(5, 175, 110, 25);
		pinBlockLabel.setBounds(5, 235, 110, 25);
		cardholderNameField.setBounds(120, 25, 360, 25);
		track1ScrollPane.setBounds(120, 55, 360, 55);
		track2ScrollPane.setBounds(120, 115, 360, 55);
		track3ScrollPane.setBounds(120, 175, 360, 55);
		pinBlockField.setBounds(120, 235, 360, 25);
		
		add(inputPanel);
		inputPanel.add(formatIdLabel);
		inputPanel.add(bdkLabel);
		inputPanel.add(ksnLabel);
		inputPanel.add(nameLabel);
		inputPanel.add(encryptedTracksLabel);
		inputPanel.add(epbLabel);
		inputPanel.add(epbKsnLabel);
		inputPanel.add(formatIdComboBox);
		inputPanel.add(bdkField);
		inputPanel.add(ksnField);
		inputPanel.add(nameField);
		inputPanel.add(encryptedTracksScrollPane);
		inputPanel.add(epbField);
		inputPanel.add(epbKsnField);
		inputPanel.add(decryptButton);
		add(outputPanel);
		outputPanel.add(cardholderNameLabel);
		outputPanel.add(track1Label);
		outputPanel.add(track2Label);
		outputPanel.add(track3Label);
		outputPanel.add(pinBlockLabel);
		outputPanel.add(cardholderNameField);
		outputPanel.add(track1ScrollPane);
		outputPanel.add(track2ScrollPane);
		outputPanel.add(track3ScrollPane);
		outputPanel.add(pinBlockField);
		
		bdkField.setText("0123456789ABCDEFFEDCBA9876543210");
		ksnField.setText("00000415130001e00006");
		nameField.setText("");
		encryptedTracksArea.setText("5b7e3f75ab27e6a88943cd596cbb4b976067c199f387ba0aa67a38f2699208c9a130e0d49e62acd026df907dacd692b402bcde12a0cc1e14b01c0c5f6f8657855491f146c7df0570e4c76dfeec6be9733c3c483ebff849ca7ea19e525a00e5ed86e1fc7439ec1303180dfddca5295915bab07d4991d56a9019f852e0676a01419fb27240624f1efed49fa5e4a534ce5818d2a125233c6733");
		formatIdComboBox.setSelectedItem("60");
		
	}
	
	public static void main(String[] args) {
		UIMain uiMain = new UIMain();
		uiMain.setSize(1010, 390);
		uiMain.setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
		uiMain.setVisible(true);
		uiMain.setTitle("Decryption Demo");
	}
}
