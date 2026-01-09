-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 27 Jul 2025 pada 19.00
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `skripsi`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `alternatif`
--

CREATE TABLE `alternatif` (
  `id_alternatif` varchar(25) NOT NULL,
  `nama_alternatif` varchar(50) NOT NULL,
  `c1` varchar(25) NOT NULL,
  `c2` varchar(25) NOT NULL,
  `c3` varchar(25) NOT NULL,
  `nama_skill` varchar(255) DEFAULT NULL,
  `tahun` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `alternatif`
--

INSERT INTO `alternatif` (`id_alternatif`, `nama_alternatif`, `c1`, `c2`, `c3`, `nama_skill`, `tahun`) VALUES
('1', 'AI & NLP Engineer', '3', '2', '1', 'User Interface,Artificial Intelligence,Scala', 2025),
('2', 'BigData Engineer', '4', '1', '4', 'User Interface,Excel,Java,SQL', 2025),
('3', 'Full-Stack Javascript Developer', '2', '2', '3', 'HTML,CSS', 2025),
('4', 'Python Web Developer', '3', '2', '2', 'Python,Django,User Interface', 2025);

-- --------------------------------------------------------

--
-- Struktur dari tabel `kriteria`
--

CREATE TABLE `kriteria` (
  `id_kriteria` int(11) NOT NULL,
  `kriteria` varchar(80) NOT NULL,
  `bobot` varchar(25) NOT NULL,
  `type` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `kriteria`
--

INSERT INTO `kriteria` (`id_kriteria`, `kriteria`, `bobot`, `type`) VALUES
(1, 'score_skill', '0.40', 'Benefit'),
(2, 'Level', '0.25', 'Benefit'),
(3, 'Involvement', '0.35', 'Benefit');

-- --------------------------------------------------------

--
-- Struktur dari tabel `laporan`
--

CREATE TABLE `laporan` (
  `id_laporan` int(255) NOT NULL,
  `id_alternatif` varchar(255) NOT NULL,
  `nama_alternatif` varchar(255) NOT NULL,
  `max_min` varchar(255) NOT NULL,
  `ranking` varchar(255) NOT NULL,
  `tanggal` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `laporan`
--

INSERT INTO `laporan` (`id_laporan`, `id_alternatif`, `nama_alternatif`, `max_min`, `ranking`, `tanggal`) VALUES
(25062401, '1', 'AI & NLP Engineer', '0.572', '1', '2025-06-24'),
(25062401, '2', 'BigData Engineer', '0.563', '2', '2025-06-24'),
(25062401, '3', 'Data Science & ML Engineer', '0.561', '3', '2025-06-24'),
(25072704, '3', 'Full-Stack Javascript Developer', '0.664', '1', '2025-07-27'),
(25072704, '2', 'BigData Engineer', '0.552', '2', '2025-07-27'),
(25072704, '1', 'AI & NLP Engineer', '0.429', '3', '2025-07-27');

-- --------------------------------------------------------

--
-- Struktur dari tabel `login`
--

CREATE TABLE `login` (
  `id` int(11) NOT NULL,
  `username` varchar(25) NOT NULL,
  `password` varchar(25) NOT NULL,
  `level` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `login`
--

INSERT INTO `login` (`id`, `username`, `password`, `level`) VALUES
(1, 'admin', 'admin', 'admin'),
(2, 'user', 'user', 'user');

-- --------------------------------------------------------

--
-- Struktur dari tabel `skills`
--

CREATE TABLE `skills` (
  `id` int(11) NOT NULL,
  `alternatif` varchar(255) NOT NULL,
  `skill_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `skills`
--

INSERT INTO `skills` (`id`, `alternatif`, `skill_name`) VALUES
(1, 'AI & NLP Engineer', 'User Interface'),
(2, 'AI & NLP Engineer', 'Artificial Intelligence'),
(3, 'AI & NLP Engineer', 'Scala'),
(4, 'AI & NLP Engineer', 'Excel'),
(5, 'AI & NLP Engineer', 'machine learning'),
(6, 'BigData Engineer', 'Artificial Intelligence'),
(7, 'BigData Engineer', 'User Interface'),
(8, 'BigData Engineer', 'Excel'),
(9, 'BigData Engineer', 'Java'),
(10, 'BigData Engineer', 'SQL'),
(11, 'Data Science & ML Engineer', 'Pandas'),
(12, 'Data Science & ML Engineer', 'NUMPY'),
(13, 'Data Science & ML Engineer', 'Python'),
(14, 'Data Science & ML Engineer', 'User Interface'),
(15, 'Data Science & ML Engineer', 'SQL'),
(16, 'Database Engineer', 'Python'),
(17, 'Database Engineer', 'NoteJS'),
(18, 'Database Engineer', 'React'),
(19, 'Database Engineer', 'Artificial Intelligence'),
(20, 'Database Engineer', 'User Interface'),
(21, 'Full-Stack Javascript Developer', 'HTML'),
(22, 'Full-Stack Javascript Developer', 'CSS'),
(23, 'Full-Stack Javascript Developer', 'machine learning'),
(24, 'Full-Stack Javascript Developer', 'User Interface'),
(25, 'Full-Stack Javascript Developer', 'Artificial Intelligence'),
(26, 'Python Web Developer', 'Python'),
(27, 'Python Web Developer', 'Django'),
(28, 'Python Web Developer', 'User Interface'),
(29, 'Python Web Developer', 'Artificial Intelligence'),
(30, 'Python Web Developer', 'Flask'),
(31, 'QA & Automation Engineer', 'React'),
(32, 'QA & Automation Engineer', 'NodeJS'),
(33, 'QA & Automation Engineer', 'ReactJS'),
(34, 'QA & Automation Engineer', 'User Interface'),
(35, 'QA & Automation Engineer', 'Artificial Intelligence'),
(36, 'Systems / Backend', 'Artificial Intelligence'),
(37, 'Systems / Backend', 'Hadoop'),
(38, 'Systems / Backend', 'Java'),
(39, 'Systems / Backend', 'User Interface'),
(40, 'Systems / Backend', 'Python');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `alternatif`
--
ALTER TABLE `alternatif`
  ADD PRIMARY KEY (`id_alternatif`);

--
-- Indeks untuk tabel `kriteria`
--
ALTER TABLE `kriteria`
  ADD PRIMARY KEY (`id_kriteria`);

--
-- Indeks untuk tabel `login`
--
ALTER TABLE `login`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `skills`
--
ALTER TABLE `skills`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `skills`
--
ALTER TABLE `skills`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
