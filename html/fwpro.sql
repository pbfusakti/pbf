-- phpMyAdmin SQL Dump
-- version 2.11.11.3
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Waktu pembuatan: 01. Oktober 2019 jam 08:32
-- Versi Server: 5.1.73
-- Versi PHP: 5.3.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `fwpro`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `dosen`
--

CREATE TABLE IF NOT EXISTS `dosen` (
  `IdDosen` int(11) NOT NULL AUTO_INCREMENT,
  `Nama` varchar(50) NOT NULL,
  `IdProgram` int(11) NOT NULL,
  PRIMARY KEY (`IdDosen`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data untuk tabel `dosen`
--

INSERT INTO `dosen` (`IdDosen`, `Nama`, `IdProgram`) VALUES
(1, 'Agung Sediyono', 1),
(2, 'Syaifudin', 2);

-- --------------------------------------------------------

--
-- Struktur dari tabel `mahasiswa`
--

CREATE TABLE IF NOT EXISTS `mahasiswa` (
  `Idmhs` int(11) NOT NULL AUTO_INCREMENT,
  `Nama` varchar(50) NOT NULL,
  `Sex` char(1) DEFAULT NULL,
  `Alamat` varchar(100) NOT NULL,
  `Nim` varchar(12) NOT NULL,
  `IdProgram` int(11) DEFAULT NULL,
  `IdWali` int(11) DEFAULT NULL,
  PRIMARY KEY (`Idmhs`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

--
-- Dumping data untuk tabel `mahasiswa`
--

INSERT INTO `mahasiswa` (`Idmhs`, `Nama`, `Sex`, `Alamat`, `Nim`, `IdProgram`, `IdWali`) VALUES
(11, 'agung sediyono', NULL, 'Tangerang Seletan', '06502001', NULL, NULL),
(12, 'agung sediyono', NULL, 'Tangerang', '06502001', NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_College`
--

CREATE TABLE IF NOT EXISTS `tbl_College` (
  `IdCollege` int(11) NOT NULL AUTO_INCREMENT,
  `NamaCollege` varchar(50) NOT NULL,
  `IdDean` int(11) NOT NULL,
  `IdUniv` int(11) NOT NULL,
  PRIMARY KEY (`IdCollege`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data untuk tabel `tbl_College`
--

INSERT INTO `tbl_College` (`IdCollege`, `NamaCollege`, `IdDean`, `IdUniv`) VALUES
(1, 'FTI', 0, 0),
(2, 'Hukum', 12, 1213);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_program`
--

CREATE TABLE IF NOT EXISTS `tbl_program` (
  `IdProgram` int(11) NOT NULL AUTO_INCREMENT,
  `NamaProgram` varchar(50) NOT NULL,
  `IdCollege` int(11) NOT NULL,
  `IdKajur` varchar(50) NOT NULL,
  `IdSekJur` varchar(50) NOT NULL,
  PRIMARY KEY (`IdProgram`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data untuk tabel `tbl_program`
--

INSERT INTO `tbl_program` (`IdProgram`, `NamaProgram`, `IdCollege`, `IdKajur`, `IdSekJur`) VALUES
(1, 'informatika', 1, 'Syaifudin', 'Anung'),
(2, 'Sistem Informasi', 1, 'Syaifudin', 'Anung'),
(3, 'Hukum', 2, 'ieren', 'budi');
